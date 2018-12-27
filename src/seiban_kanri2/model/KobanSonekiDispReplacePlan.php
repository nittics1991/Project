<?php
/**
*   KobanSonekiDispReplacePlan
*
*   @version 171115
*/
namespace seiban_kanri2\model;

use \Exception;
use \PDO;
use \RuntimeException;
use Concerto\database\CyubanInf;
use Concerto\database\KobanInf;
use Concerto\database\OperationHist;
use Concerto\database\OperationHistData;

class KobanSonekiDispReplacePlan
{
    /**
    *   object
    *
    *   @val object
    */
    private $pdo;
    private $cyubanInf;
    private $kobanInf;
    private $operationHist;
    private $operationHistData;
    
    /**
    *   __construct
    *
    *   @param PDO $pdo
    *   @param CyubanInf $cyubanInf
    *   @param KobanInf $kobanInf
    *   @param OperationHist $operationHist
    *   @param OperationHistData $operationHistData
    */
    public function __construct(
        PDO $pdo,
        CyubanInf $cyubanInf,
        KobanInf $kobanInf,
        OperationHist $operationHist,
        OperationHistData $operationHistData
    ) {
        $this->pdo = $pdo;
        $this->cyubanInf = $cyubanInf;
        $this->kobanInf = $kobanInf;
        $this->operationHist = $operationHist;
        $this->operationHistData = $operationHistData;
    }
    
    /**
    *   過去月計画値実績置換
    *
    *   @param string $input_code 担当コード
    *   @param string $no_cyu 注番
    */
    public function replace($input_code, $no_cyu)
    {
        $sqlCom = "
            SELECT no_cyu, no_ko
            FROM public.koban_inf A
            JOIN public.mst_tanto B
                ON B.kb_group = A.cd_bumon
            WHERE A.no_cyu = :cyuban
                AND B.tanto_code = :tanto
        ";
        
        $sqlDel = "
            WITH condition AS (
                {$sqlCom}
            )
            DELETE
            FROM public.cyunyu_inf M
            USING condition N
            WHERE M.no_cyu = N.no_cyu
                AND  M.no_ko = N.no_ko
                AND M.kb_cyunyu = '0'
                AND M.dt_kanjyo <= :yyyymm
        ";
        
        $sqlIns = "
            WITH condition AS (
                {$sqlCom}
            )
            INSERT INTO public.cyunyu_inf
                (kb_nendo, id_project, no_cyu, no_ko, dt_kanjyo
                , cd_genka_yoso, cd_tanto, dt_cyunyu, tm_cyokka, yn_cyokka
                , yn_cyokuzai, yn_ryohi, yn_etc, nm_tanto, nm_syohin
                , kb_cyunyu
                , cd_bumon, no_cyumon
                , no_seq
                , cd_bumon_dmy, fg_kanjyo
                , up_date
                , cd_rev
                , no_tehai,nm_tehai,cd_furikae,no_cyu_furikae,no_ko_furikae
                )
            SELECT
                kb_nendo, id_project, M.no_cyu, M.no_ko, dt_kanjyo
                , cd_genka_yoso, cd_tanto, dt_cyunyu, tm_cyokka, yn_cyokka
                , yn_cyokuzai, yn_ryohi, yn_etc, nm_tanto, nm_syohin
                , '0'
                , cd_bumon, no_cyumon
                , Q.no_seq + row_number() OVER ()
                , cd_bumon_dmy, fg_kanjyo
                , TO_CHAR(now(), 'YYYYMMDD HH24MISS')
                , P.tanto_code
                , no_tehai,nm_tehai,cd_furikae,no_cyu_furikae,no_ko_furikae
            FROM public.cyunyu_inf M
            JOIN condition N
                ON M.no_cyu = N.no_cyu
                    AND  M.no_ko = N.no_ko
            JOIN
                (SELECT tanto_code
                FROM public.mst_tanto
                WHERE tanto_code = :tanto
                ) P
                ON 1 = 1
            JOIN
                (SELECT no_cyu, no_ko, MAX(no_seq) AS no_seq
                FROM public.cyunyu_inf
                WHERE no_cyu = :cyuban
                GROUP BY no_cyu, no_ko
                ) Q
                ON Q.no_cyu = M.no_cyu
                    AND Q.no_ko = M.no_ko
            WHERE M.kb_cyunyu = '1'
                AND dt_kanjyo <= :yyyymm
        ";
        
        $yyyymm = date('Ym', strtotime('-1 month'));
        
        try {
            $this->pdo->beginTransaction();
            
            $stmt = $this->pdo->prepare($sqlDel);
            $stmt->bindValue(':cyuban', $no_cyu, PDO::PARAM_STR);
            $stmt->bindValue(':tanto', $input_code, PDO::PARAM_STR);
            $stmt->bindValue(':yyyymm', $yyyymm, PDO::PARAM_STR);
            $stmt->execute();
            
            $stmt = $this->pdo->prepare($sqlIns);
            $stmt->bindValue(':cyuban', $no_cyu, PDO::PARAM_STR);
            $stmt->bindValue(':tanto', $input_code, PDO::PARAM_STR);
            $stmt->bindValue(':yyyymm', $yyyymm, PDO::PARAM_STR);
            $stmt->execute();
            
            //操作履歴
            $operationHistData = clone $this->operationHistData;
            $operationHistData->ins_date = date('Ymd_His');
            $operationHistData->cd_tanto = $input_code;
            $operationHistData->nm_table = '1';
            $operationHistData->no_cyu = $no_cyu;
            $operationHistData->nm_after = "【計画置換】{$no_cyu}";
            $this->operationHist->insert([$operationHistData]);
            
            //koban_inf集計
            $this->kobanInf->aggregate($no_cyu, $no_ko);
            
            //cyuban_inf kb_keikaku更新
            $this->cyubanInf->updateKbKeikaku($no_cyu);
            
            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
