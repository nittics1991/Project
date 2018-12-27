<?php
/**
*   CyunyuInfDispMntModel
*
*   @version 181116
*/
namespace seiban_kanri2\model;

use \Exception;
use \PDO;
use Concerto\database\CyokkaKeikaku;
use Concerto\database\CyokkaKeikakuData;
use Concerto\database\CyubanInf;
use Concerto\database\CyunyuInf;
use Concerto\database\CyunyuInfData;
use Concerto\database\KobanInf;
use Concerto\database\KobanInfData;
use Concerto\database\MstTanto;
use Concerto\database\MstTantoData;
use Concerto\database\OperationHist;
use Concerto\database\OperationHistData;
use Concerto\database\SeibanTanto;
use Concerto\database\SeibanTantoData;
use Concerto\standard\Session;
use Concerto\FiscalYear;

class CyunyuInfDispMntModel
{
    /**
    *   object
    *
    *   @val object
    */
    private $pdo;
    private $cyokkaKeikaku;
    private $cyokkaKeikakuData;
    private $cyubanInf;
    private $cyunyuInf;
    private $cyunyuInfData;
    private $kobanInf;
    private $kobanInfData;
    private $mstTanto;
    private $mstTantoData;
    private $operationHist;
    private $operationHistData;
    private $seibanTanto;
    private $seibanTantoData;
    private $globalSession;
    private $session;
    
    /**
    *   __construct
    *
    *   @param PDO $pdo
    *   @param CyokkaKeikaku $cyokkaKeikaku
    *   @param CyokkaKeikakuData $cyokkaKeikakuData
    *   @param CyubanInf $cyubanInf
    *   @param CyunyuInf $cyunyuInf
    *   @param CyunyuInfData $cyunyuInfData
    *   @param KobanInf $kobanInf
    *   @param KobanInfData $kobanInfData
    *   @param MstTanto $mstTanto
    *   @param MstTantoData $mstTantoData
    *   @param OperationHist $operationHist
    *   @param OperationHistData $operationHistData
    *   @param SeibanTanto $seibanTanto
    *   @param SeibanTantoData $seibanTantoData
    *   @param Session $globalSession
    *   @param Session $session
    */
    public function __construct(
        PDO $pdo,
        CyokkaKeikaku $cyokkaKeikaku,
        CyokkaKeikakuData $cyokkaKeikakuData,
        CyubanInf $cyubanInf,
        CyunyuInf $cyunyuInf,
        CyunyuInfData $cyunyuInfData,
        KobanInf $kobanInf,
        KobanInfData $kobanInfData,
        MstTanto $mstTanto,
        MstTantoData $mstTantoData,
        OperationHist $operationHist,
        OperationHistData $operationHistData,
        SeibanTanto $seibanTanto,
        SeibanTantoData $seibanTantoData,
        Session $globalSession,
        Session $session
    ) {
        $this->pdo = $pdo;
        $this->cyokkaKeikaku = $cyokkaKeikaku;
        $this->cyokkaKeikakuData = $cyokkaKeikakuData;
        $this->cyubanInf = $cyubanInf;
        $this->cyunyuInf = $cyunyuInf;
        $this->cyunyuInfData = $cyunyuInfData;
        $this->kobanInf = $kobanInf;
        $this->kobanInfData = $kobanInfData;
        $this->mstTanto = $mstTanto;
        $this->mstTantoData = $mstTantoData;
        $this->operationHist = $operationHist;
        $this->operationHistData = $operationHistData;
        $this->seibanTanto = $seibanTanto;
        $this->seibanTantoData = $seibanTantoData;
        $this->globalSession = $globalSession;
        $this->session = $session;
    }
    
    /**
    *   注入データ更新
    *
    *   @param PostCyunyuInfDisp $post
    */
    public function setCyunyuData(PostCyunyuInfDisp $post)
    {
        $input_code = $this->globalSession->input_code;
        $cd_bumon = $this->session->cd_bumon;
        $kb_nendo = $this->session->kb_nendo;
        $no_cyu = $this->session->no_cyu;
        $no_ko = $this->session->no_ko;
        
        $act = $post->act?? '';
        $no_cyu = $post->no_cyu?? $no_cyu;
        $no_ko = $post->no_ko?? $no_ko;
        $cd_bumon = $post->cd_bumon?? $cd_bumon;
        $no_seq = $post->no_seq?? '0';
        $cd_genka_yoso = $post->cd_genka_yoso?? 'B';
        $cd_tanto = $post->cd_tanto?? 'XXXXXXXX';
        $nm_tanto = $post->nm_tanto?? null;
        $nm_syohin = $post->nm_syohin?? null;
        $num_data[0] = $post->num_data[0]?? '0';
        $num_data[1] = $post->num_data[1]?? '0';
        $num_data[2] = $post->num_data[2]?? '0';
        $num_data[3] = $post->num_data[3]?? '0';
        $num_data[4] = $post->num_data[4]?? '0';
        $num_data[5] = $post->num_data[5]?? '0';
        $num_data[6] = $post->num_data[6]?? '0';
        $num_data[7] = $post->num_data[7]?? '0';
        $num_data[8] = $post->num_data[8]?? '0';
        $num_data[9] = $post->num_data[9]?? '0';
        $num_data[10] = $post->num_data[10]?? '0';
        $num_data[11] = $post->num_data[11]?? '0';
        
        $kb_nendos[0] = $post->kb_nendo?? $kb_nendo;
        $kb_nendos[1] = FiscalYear::getNextNendo($kb_nendos[0]);
        
        $flg = true;
        
        //部門コード・担当名称
        if ($cd_tanto != "XXXXXXXX") {
            $mstTantoData = clone $this->mstTantoData;
            $mstTantoData->cd_tanto = $cd_tanto;
            $result = $this->mstTanto->select($mstTantoData);
            
            if (count($result) != 1) {
                $flg = false;
            } else {
                $obj = $result[0];
                $cd_bumon = $obj->cd_bumon;
                $nm_tanto = $obj->nm_tanto;
            }
        } else {
            $kobanInfData = clone $this->kobanInfData;
            $kobanInfData->no_cyu = $no_cyu;
            $kobanInfData->no_ko = $no_ko;
            $result = $this->kobanInf->select($kobanInfData);
            
            if (count($result) != 1) {
                $flg = false;
            } else {
                $obj = $result[0];
                $cd_bumon = $obj->cd_bumon;
            }
        }
        
        //直課単価
        $cyokkaKeikakuData = clone $this->cyokkaKeikakuData;
        $cyokkaKeikakuData->cd_bumon = $cd_bumon;
        
        $cyokkaKeikakuData->kb_nendo = $kb_nendos[0];
        $result = $this->cyokkaKeikaku->select($cyokkaKeikakuData);
        
        if (count($result) != 1) {
            $yn_tanka[0] = 5900;
        } else {
            $obj = $result[0];
            $yn_tanka[0] = $obj->yn_tanka;
        }
        
        $cyokkaKeikakuData->kb_nendo = $kb_nendos[1];
        $result = $this->cyokkaKeikaku->select($cyokkaKeikakuData);

        if (count($result) != 1) {
            $yn_tanka[1] = 5900;
        } else {
            $obj = $result[0];
            $yn_tanka[1] = $obj->yn_tanka;
        }
        
        //注入年月
        $dt_yyyymm = array_merge(
            FiscalYear::getNendoyyyymm($kb_nendos[0]),
            FiscalYear::getNendoyyyymm($kb_nendos[1])
        );
        
        //更新日時
        $up_date = date('Ymd His');
        
        if ($flg) {
            try {
                $this->pdo->beginTransaction();
                
                $cyunyuInfData = clone $this->cyunyuInfData;
                $cyunyuInfData->no_cyu = $no_cyu;
                $cyunyuInfData->no_ko = $no_ko;
                
                $seibanTantoData = clone $this->seibanTantoData;
                $seibanTantoData->no_cyu = $no_cyu;
                $seibanTantoData->no_ko = $no_ko;
                    
                $operationHistData = clone $this->operationHistData;
                $operationHistData->ins_date = date('Ymd_His');
                $operationHistData->cd_tanto = $input_code;
                $operationHistData->nm_table = '1';
                $operationHistData->no_cyu = $no_cyu;
                
                switch ($act) {
                    case 'insert':
                        $no_seq = $this->cyunyuInf->getMaxNoSeq(
                            $no_cyu,
                            $no_ko
                        ) + 1;
                        $cyunyuInfData->cd_genka_yoso = $cd_genka_yoso;
                        $cyunyuInfData->cd_tanto = $cd_tanto;
                        $cyunyuInfData->dt_cyunyu = '';
                        $cyunyuInfData->yn_ryohi = 0;
                        $cyunyuInfData->nm_tanto = $nm_tanto;
                        $cyunyuInfData->nm_syohin = $nm_syohin;
                        $cyunyuInfData->kb_cyunyu = '0';
                        $cyunyuInfData->cd_bumon = $cd_bumon;
                        $cyunyuInfData->no_seq = $no_seq;
                        $cyunyuInfData->up_date = $up_date;
                        $cyunyuInfData->cd_rev = $input_code;
                        
                        $this->operationHistData->nm_before = '';
                        
                        $cyunyu_list = [];
                        $operation_list = [];
                        
                        for ($i = 0; $i < count($num_data); $i++) {
                            if ($num_data[$i] != 0) {
                                $obj = clone $cyunyuInfData;
                                $obj->dt_kanjyo = $dt_yyyymm[$i];
                                
                                if ($i < 6) {
                                    $kb_nendo_tmp = $kb_nendos[0];
                                    $yn_tanka_tmp = $yn_tanka[0];
                                } else {
                                    $kb_nendo_tmp = $kb_nendos[1];
                                    $yn_tanka_tmp = $yn_tanka[1];
                                }
                                
                                switch ($cd_genka_yoso) {
                                    case 'A':
                                        $obj->yn_cyokuzai = (integer)$num_data[$i];
                                        $obj->tm_cyokka = 0.0;
                                        $obj->yn_cyokka = 0;
                                        $obj->yn_etc = 0;
                                        break;
                                    case 'B':
                                        $obj->yn_cyokuzai = 0;
                                        $obj->tm_cyokka = (double)$num_data[$i];
                                        $obj->yn_cyokka =
                                            (integer)($num_data[$i] * $yn_tanka_tmp);
                                        break;
                                        $obj->yn_etc = 0;
                                    case 'C':
                                        $obj->yn_cyokuzai = 0;
                                        $obj->tm_cyokka = 0.0;
                                        $obj->yn_cyokka = 0;
                                        $obj->yn_etc = (integer)$num_data[$i];
                                }
                                
                                $obj->kb_nendo = $kb_nendo_tmp;
                                
                                $cyunyu_list[] = $obj;
                                
                                $obj2 = clone $operationHistData;
                                $obj2->nm_after =
                                    "【新規】{$no_cyu}{$no_ko} 年月:{$dt_yyyymm[$i]} 要素:{$cd_genka_yoso} 注入先:{$nm_tanto} 商品:{$nm_syohin} 値:{$num_data[$i]}";
                                
                                $operation_list[] = $obj2;
                            }
                        }
                        
                        $this->cyunyuInf->insert($cyunyu_list);
                        $this->operationHist->insert($operation_list);
                        
                        if ($cd_genka_yoso == 'B') {
                            $seibanTantoData->ins_date = $up_date;
                            $seibanTantoData->no_seq = $no_seq;
                            $seibanTantoData->cd_tanto = $cd_tanto;
                        
                            $this->seibanTanto->insert([$seibanTantoData]);
                        }
                        break;
                    case 'update':
                        $cyunyuInfData->no_seq = $no_seq;
                        $result = $this->cyunyuInf->select($cyunyuInfData);
                        
                        $insert_list = [];
                        $operation_list = [];
                        
                        for ($i = 0; $i < count($num_data); $i++) {
                            $data = clone $cyunyuInfData;
                            $data->up_date = $up_date;
                            $data->cd_rev = $input_code;
                            
                            if ($i < 6) {
                                $yn_tanka_tmp = $yn_tanka[0];
                                $kb_nendo_tmp = $kb_nendos[0];
                            } else {
                                $yn_tanka_tmp = $yn_tanka[1];
                                $kb_nendo_tmp = $kb_nendos[1];
                            }
                            
                            switch ($cd_genka_yoso) {
                                case 'A':
                                    $data->yn_cyokuzai = (integer)$num_data[$i];
                                    $data->tm_cyokka = 0.0;
                                    $data->yn_cyokka = 0;
                                    $data->yn_etc = 0;
                                    break;
                                case 'B':
                                    $data->yn_cyokuzai = 0;
                                    $data->tm_cyokka = (double)$num_data[$i];
                                    $data->yn_cyokka =
                                        (integer)($num_data[$i] * $yn_tanka_tmp);
                                    $data->yn_etc = 0;
                                    break;
                                case 'C':
                                    $data->yn_cyokuzai = 0;
                                    $data->tm_cyokka = 0.0;
                                    $data->yn_cyokka = 0;
                                    $data->yn_etc = (integer)$num_data[$i];
                            }
                            
                            $where = clone $cyunyuInfData;
                            $where->dt_kanjyo = $dt_yyyymm[$i];
                            
                            $obj2 = clone $operationHistData;
                            $exists = false;
                            
                            //既設検索
                            foreach ((array)$result as $obj1) {
                                //既登録あり
                                if ($obj1->dt_kanjyo == $dt_yyyymm[$i]) {
                                    $nm_syohin_old = $obj1->nm_syohin;
                                    
                                    switch ($cd_genka_yoso) {
                                        case 'A':
                                            $num_data_old = $obj1->yn_cyokuzai;
                                            break;
                                        case 'B':
                                            $num_data_old = $obj1->tm_cyokka;
                                            break;
                                        case 'C':
                                            $num_data_old = $obj1->yn_etc;
                                            break;
                                    }
                                    
                                    //データ０＝＞削除
                                    if ($num_data[$i] == 0) {
                                        $this->cyunyuInf->delete([$where]);
                                        
                                        $obj2->nm_before =
                                            "【削除】{$no_cyu}{$no_ko} 年月:{$dt_yyyymm[$i]} 要素:{$cd_genka_yoso} 注入先:{$nm_tanto} 商品:{$nm_syohin} 値:{$num_data_old}";
                                        $obj2->nm_after =
                                            "【削除】{$no_cyu}{$no_ko}";
                                        
                                        $operation_list[] = $obj2;
                                    //データ更新
                                    } elseif ($num_data_old != $num_data[$i]) {
                                        $this->cyunyuInf->update([[$data, $where]]);
                                        
                                        $obj2->nm_before =
                                            "【更新】{$no_cyu}{$no_ko} 年月:{$dt_yyyymm[$i]} 要素:{$cd_genka_yoso} 注入先:{$nm_tanto} 商品:{$nm_syohin_old} 値:{$num_data_old}";
                                        $obj2->nm_after =
                                            "【更新】{$no_cyu}{$no_ko} 年月:{$dt_yyyymm[$i]} 要素:{$cd_genka_yoso} 注入先:{$nm_tanto} 商品:{$nm_syohin} 値:{$num_data[$i]}";
                                        
                                        $operation_list[] = $obj2;
                                    }
                                    $exists = true;
                                }
                            }
                            
                            //新規
                            if (!$exists) {
                                if ($num_data[$i] != 0) {
                                    $data->cd_genka_yoso = $cd_genka_yoso;
                                    $data->cd_tanto = $cd_tanto;
                                    $data->dt_cyunyu = '';
                                    $data->yn_ryohi = 0;
                                    $data->nm_tanto = $nm_tanto;
                                    $data->nm_syohin = $nm_syohin;
                                    $data->kb_cyunyu = '0';
                                    $data->cd_bumon = $cd_bumon;
                                    $data->no_seq = $no_seq;
                                    $data->up_date = $up_date;
                                    $data->cd_rev = $input_code;
                                    
                                    $data->dt_kanjyo = $dt_yyyymm[$i];
                                    $data->kb_nendo = $kb_nendo_tmp;
                                    
                                    $insert_list[] = $data;
                                    
                                    $obj2->nm_before = '';
                                    $obj2->nm_after =
                                        "【新規】{$no_cyu}{$no_ko} 年月:{$dt_yyyymm[$i]} 要素:{$cd_genka_yoso} 注入先:{$nm_tanto} 商品:{$nm_syohin} 値:{$num_data[$i]}";
                                    
                                    $operation_list[] = $obj2;
                                }
                            }
                        }
                        
                        //新規追加がある場合、一括追加
                        if (count($insert_list) > 0) {
                            $this->cyunyuInf->insert($insert_list);
                        }
                        
                        //データが無くなったら製番担当削除
                        if ($cd_genka_yoso == 'B') {
                            $result = $this->cyunyuInf->select($cyunyuInfData);
                            
                            if (count($result) == 0) {
                                $seibanTantoData->no_seq = $no_seq;
                                $this->seibanTanto->delete([$seibanTantoData]);
                            }
                        }
                        
                        $this->operationHist->insert($operation_list);
                        
                        break;
                    case 'delete':
                        $cyunyuInfData->no_seq = $no_seq;
                        $result = $this->cyunyuInf->select($cyunyuInfData);
                        
                        $operationHistData->nm_after = "";
                        $operation_list = [];
                        
                        foreach ((array)$result as $obj) {
                            $obj2 = clone $operationHistData;
                            
                            switch ($obj->cd_genka_yoso) {
                                case 'A':
                                    $num_data = $obj->yn_cyokuzai;
                                    break;
                                case 'B':
                                    $num_data = $obj->tm_cyokka;
                                    break;
                                case 'C':
                                    $num_data = $obj->yn_etc;
                                    break;
                                default:
                                    $num_data = '';
                            }
                            
                            $obj2->nm_before =
                                "【削除】{$obj->no_cyu}{$obj->no_ko} 年月:{$obj->dt_kanjyo} 要素:{$obj->cd_genka_yoso} 注入先:{$obj->nm_tanto} 商品:{$obj->nm_syohin} 値:{$num_data}";
                            $obj2->nm_after =
                                "【削除】{$obj->no_cyu}{$obj->no_ko}";
                            
                            $operation_list[] = $obj2;
                        }
                        
                        $this->cyunyuInf->delete([$cyunyuInfData]);
                        $this->operationHist->insert($operation_list);
                        
                        if ($cd_genka_yoso == 'B') {
                            $seibanTantoData->no_seq = $no_seq;
                            $this->seibanTanto->delete([$seibanTantoData]);
                        }
                        break;
                }
                
                //koban_inf集計
                $this->kobanInf->aggregate($no_cyu, $no_ko);
                
                //cyuban_inf kb_keikaku更新
                $this->cyubanInf->updateKbKeikaku($no_cyu);
                
                $this->pdo->commit();
            } catch (Exception $e) {
                $this->pdo->rollback();
                throw $e;
            }
        }
    }
}
