<?php
/**
*   Model
*
*   @version 171018
*/
namespace seiban_kanri2\model;

use \InvalidArgumentException;
use \PDO;
use \RuntimeException;
use \SplFileObject;
use Concerto\database\CyunyuKeikaku;
use Concerto\database\CyunyuKeikakuData;
use Concerto\database\CyunyuInfData;
use Concerto\database\KobanInf;
use Concerto\database\OperationHist;
use Concerto\database\OperationHistData;
use Concerto\standard\Session;
use Concerto\standard\ArrayUtil;

class CyubanSonekiExcelReadModel
{
    /**
    *   object
    *
    *   @val object
    */
    private $pdo;
    private $cyunyuInfData;
    private $cyunyuKeikaku;
    private $cyunyuKeikakuData;
    private $kobanInf;
    private $operationHist;
    private $operationHistData;
    private $globalSession;
    
    /**
    *   __construct
    *
    *   @param PDO $pdo
    *   @param CyunyuInfData $cyunyuInfData
    *   @param CyunyuKeikaku $cyunyuKeikaku
    *   @param CyunyuKeikakuData $cyunyuKeikakuData
    *   @param KobanInf $kobanInf
    *   @param OperationHist $operationHist
    *   @param OperationHistData $operationHistData
    *   @param Session $globalSession
    */
    public function __construct(
        PDO $pdo,
        CyunyuInfData $cyunyuInfData,
        CyunyuKeikaku $cyunyuKeikaku,
        CyunyuKeikakuData $cyunyuKeikakuData,
        KobanInf $kobanInf,
        OperationHist $operationHist,
        OperationHistData $operationHistData,
        Session $globalSession
    ) {
        $this->pdo = $pdo;
        $this->cyunyuKeikaku = $cyunyuKeikaku;
        $this->cyunyuKeikakuData = $cyunyuKeikakuData;
        $this->cyunyuInfData = $cyunyuInfData;
        $this->kobanInf = $kobanInf;
        $this->operationHist = $operationHist;
        $this->operationHistData = $operationHistData;
        $this->globalSession = $globalSession;
    }
    
    /**
    *   CSVインポート
    *
    *   @param string $csv CSVファイルパス
    *   @return array 実行結果
    *   @throws InvalidArgumentException, RuntimeException
    **/
    public function importCSV($csv)
    {
        if (!file_exists($csv)) {
            throw new InvalidArgumentException("import CSV not exists");
        }
        
        $work_csv = dirname($csv) . '\\' . uniqid() . 'work.csv';
        $valid_error = $this->validAndBuildCsv($csv, $work_csv);
        
        if (!empty($valid_error)) {
            return $valid_error;
        }
        
        try {
            $this->cyunyuKeikaku->truncate();
        } catch (Exception $e) {
            throw new RuntimeException("tmp table truncate error", 0, $e);
        }
        
        try {
            $this->importCyunyuKeikaku($work_csv);
            $valid_error = $this->checkCyunyuKeikakuData();
            if (!empty($valid_error)) {
                return $valid_error;
            }
        } catch (Exception $e) {
            throw new RuntimeException("check tmp table valid error", 0, $e);
        }
        
        try {
            $this->pdo->beginTransaction();
            
            $this->deleteCyunyuInf();
            $hist = $this->insertCyunyuInf();
            
            if (count($hist) > 0) {
                $this->updateKobanInf($hist);
                $this->updateOperationHist($hist);
            }
            
            $this->pdo->commit();
            
            @unlink($csv);
            @unlink($work_csv);
        } catch (Exception $e) {
            $this->pdo->rollback();
            throw $e;
        }
        return $valid_error;
    }
    
    /**
    *   CSVチェック&加工出力
    *
    *   @param string $in 元CSVファイルパス
    *   @param string $out 加工後CSVファイルパス
    *   @return array エラーデータ ['no_id' => ['prop' => 'message']]
    *   @throws RuntimeException
    **/
    private function validAndBuildCsv($in, $out)
    {
        $input_code = $this->globalSession->input_code;
        
        $cyunyuKeikakuData = clone $this->cyunyuKeikakuData;
        $update = date('Ymd His');
        $editor = $input_code;
        $result = [];
        
        $file_in = new SplFileObject($in, 'r');
        $file_in->setFlags(SplFileObject::READ_CSV);
        $file_out = new SplFileObject($out, 'w');
        $count = 0;
        
        foreach ($file_in as $data) {
            if ($data === false) {
                throw new RuntimeException("EXCEL CSV read error");
            }
            
            //先頭行/空行削除
            if (($count == 0) || (mb_strlen(trim(implode('', $data))) == 0)) {
                $count++;
                continue;
            }
            
            mb_convert_variables('UTF-8', 'SJIS', $data);
            
            $obj = clone $cyunyuKeikakuData;
            $obj->fromNumberArray(array_merge(array($update, $editor), $data));
            
            if (!$obj->isValid()) {
                $error = $obj->getValidError();
                
                $no_id = ($obj->isNull())?  $count:$obj->no_id;
                $result[$no_id] = $error;
            } else {
                if ($this->writeCsv($file_out, $obj->toNumberArray()) === false) {
                    throw new RuntimeException("EXCEL CSV write error");
                }
            }
            $count++;
        }
        
        $file_in = null;
        $file_out= null;
        
        if ($count > 1000) {
            $result[1001] ='line over';
        }
        
        if ($count = 0) {
            $result[0] = 'data not found';
        }
        return $result;
    }
    
    /**
    *   CSV出力
    *
    *   @param SplFileObject $file
    *   @param array $data データ
    *   @return bool
    **/
    private function writeCsv(SplFileObject $file, array $data)
    {
        if (empty($data)) {
            return true;
        }
        
        $escaped = array_map(
            function ($val) {
                $escaped = mb_ereg_replace('\\\\', '\\', $val);
                $delimiter = mb_ereg_replace(',', '\,', $escaped);
                return mb_ereg_replace('"', '\\"', $delimiter);
            },
            $data
        );
        
        $line = implode(',', $escaped) . "\r\n";
        return $file->fwrite($line);
    }
    
    /**
    *   CSV=>cyunyu_keikakuインポート
    *
    *   @param string $csv CSVファイルパス
    **/
    private function importCyunyuKeikaku($csv)
    {
        $this->cyunyuKeikaku->import($csv);
    }
    
    /**
    *   cyunyu_keikakuチェック
    *
    *   @return array エラーデータ ['no_id' => ['prop' => 'message']]
    *   @throws RuntimeException
    **/
    private function checkCyunyuKeikakuData()
    {
        $sql = "
            SELECT no_id, no_cyu, no_ko 
            FROM tmp.cyunyu_keikaku A 
            WHERE NOT EXISTS
                (SELECT * 
                FROM tmp.cyunyu_keikaku B1 
                JOIN public.koban_inf B2 
                    ON B2.no_cyu = B1.no_cyu 
                        AND B2.no_ko = B1.no_ko 
                
                WHERE B1.no_cyu = A.no_cyu 
                    AND B1.no_ko = A.no_ko 
            )
            ORDER BY no_id, no_cyu, no_ko
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        if (count($result) == 0) {
            return [];
        }
        
        $valid_error = [];
        foreach ($result as $list) {
            $no_id = $list['no_id'];
            $columns = array($list['no_cyu'] => '', $list['no_ko'] => '');
            $valid_error[$no_id] = $columns;
        }
        return $valid_error;
    }
    
    /**
    *   cyunyu_inf削除
    *
    *   @throws RuntimeException
    **/
    private function deleteCyunyuInf()
    {
        $sql = "
            DELETE FROM public.cyunyu_inf A 
            WHERE EXISTS 
                (SELECT * 
                FROM tmp.cyunyu_keikaku B 
                WHERE B.no_cyu = A.no_cyu 
                    AND B.no_ko = A.no_ko 
                    AND B.dt_kanjyo = A.dt_kanjyo 
                    AND A.kb_cyunyu = '0'
                )
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
    }
    
    /**
    *   cyunyu_inf挿入
    *
    *   @return array 挿入履歴 ['no_cyu', 'no_ko']
    *   @throws RuntimeException
    **/
    private function insertCyunyuInf()
    {
        $cyunyuInfData = clone $this->cyunyuInfData;
        $columns = array_keys($cyunyuInfData->getInfo());
        
        $sql = "
            INSERT INTO public.cyunyu_inf 
                (kb_nendo, no_cyu, no_ko, dt_kanjyo, cd_genka_yoso, cd_tanto
                , tm_cyokka, yn_cyokka , yn_cyokuzai, yn_etc
                , nm_tanto, nm_syohin
                , kb_cyunyu, cd_bumon, no_seq
                , up_date, cd_rev
                )
            (SELECT 
                CASE 
                    WHEN CAST(SUBSTR(dt_kanjyo, 5, 2) AS INTEGER) >= 1
                        AND CAST(SUBSTR(dt_kanjyo, 5, 2) AS INTEGER) <= 3 
                        THEN CAST((CAST(SUBSTR(dt_kanjyo, 1, 4) AS INTEGER) - 1) AS TEXT) || 'S'
                    WHEN CAST(SUBSTR(dt_kanjyo, 5, 2) AS INTEGER) >= 10
                        AND CAST(SUBSTR(dt_kanjyo, 5, 2) AS INTEGER) <= 12 
                        THEN SUBSTR(dt_kanjyo, 1, 4) || 'S'
                    ELSE SUBSTR(dt_kanjyo, 1, 4) || 'K'
                    END AS kb_nendo
            , A.no_cyu, A.no_ko, dt_kanjyo, cd_genka_yoso
                , CASE cd_genka_yoso 
                    WHEN 'B' THEN nm_cyunyu
                    ELSE 'XXXXXXXX'
                    END AS cd_tanto 
                , tm_cyokka
                , CASE cd_genka_yoso 
                    WHEN 'B' THEN yn_money 
                    ELSE 0
                    END AS yn_cyokka
                , CASE cd_genka_yoso 
                    WHEN 'A' THEN yn_money 
                    ELSE 0
                    END AS yn_cyokuzai
                , CASE cd_genka_yoso 
                    WHEN 'C' THEN yn_money 
                    ELSE 0
                    END AS yn_etc
                , CASE cd_genka_yoso 
                    WHEN 'B' THEN tanto_name 
                    ELSE nm_cyunyu 
                    END AS nm_tanto
                , nm_syohin
                , '0' AS kb_cyunyu
                , cd_bumon
                , COALESCE(max_no_seq, 0) + (row_number() OVER())  AS max_no_seq
                , TO_CHAR(NOW(), 'YYYYMMDD HH24MISS')
                , editor 
            FROM (
                SELECT editor, no_cyu, no_ko, cd_genka_yoso
                    , dt_kanjyo, nm_cyunyu, nm_syohin, cd_bumon 
                    , SUM(tm_cyokka) AS tm_cyokka, SUM(yn_money) AS yn_money
                FROM tmp.cyunyu_keikaku 
                GROUP BY editor, no_cyu, no_ko, cd_genka_yoso
                    , dt_kanjyo, nm_cyunyu, nm_syohin, cd_bumon 
                ) A 
            LEFT JOIN public.mst_tanto B 
                ON B.tanto_code = A.nm_cyunyu 
            LEFT JOIN 
                (SELECT no_cyu, no_ko 
                    , MAX(no_seq) AS max_no_seq
                FROM public.cyunyu_inf 
                GROUP BY no_cyu, no_ko 
                ) C
                ON C.no_cyu = A.no_cyu 
                    AND C.no_ko = A.no_ko 
            )
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        
        $cyunyuKeikakuData = clone $this->cyunyuKeikakuData;
        $result = $this->cyunyuKeikaku->groupBy(
            'no_cyu, no_ko',
            $cyunyuKeikakuData,
            'no_cyu, no_ko'
        );
        
        if (count($result) == 0) {
            return [];
        }
        
        $items = [];
        foreach ($result as $obj) {
            $no_cyu = $obj->no_cyu;
            $no_ko = $obj->no_ko;
            $items[] = ['no_cyu' => $no_cyu, 'no_ko' => $no_ko];
        }
        return $items;
    }
    
    /**
    *   kobanInf更新
    *
    *   @param array $hist 変更情報 ['no_cyu', 'no_ko']
    **/
    private function updateKobanInf(array $hist)
    {
        if (count($hist) == 0) {
            return;
        }
        
        $cyubans = ArrayUtil::selectBy($hist, ['no_cyu']);
        $transverse = ArrayUtil::transverse($cyubans);
        $unique = array_unique($transverse);
        
        foreach ((array)$unique as $list) {
            $this->kobanInf->aggregate($list['no_cyu']);
        }
    }
    
    /**
    *   履歴保存
    *
    *   @param array $hist 変更情報 ['no_cyu', 'no_ko']
    *   @throws RuntimeException
    **/
    private function updateOperationHist(array $hist)
    {
        $input_code = $this->globalSession->input_code;
        
        $operationHistData = clone $this->operationHistData;
        $operationHistData->ins_date = date('Ymd_His');
        $operationHistData->cd_tanto = $input_code;
        $operationHistData->nm_table = '1';
        $operation_list = [];
        
        foreach ($hist as $list) {
            $obj = clone $operationHistData;
            $obj->no_cyu = $list['no_cyu'];
            $obj->nm_after =
                "【インポート】{$list['no_cyu']}{$list['no_ko']}";
            array_push($operation_list, $obj);
        }
        $this->operationHist->insert($operation_list);
    }
}
