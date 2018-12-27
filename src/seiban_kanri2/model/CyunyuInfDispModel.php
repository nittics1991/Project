<?php
/**
*   FacadeModel
*
*   @version 180507
*/
namespace seiban_kanri2\model;

use \Exception;
use \PDO;
use Concerto\database\CyubanInf;
use Concerto\database\CyubanInfData;
use Concerto\database\CyunyuInf;
use Concerto\database\CyunyuInfData;
use Concerto\database\KobanInf;
use Concerto\database\KobanInfData;
use Concerto\database\MstBumon;
use Concerto\database\MstBumonData;
use Concerto\database\MstTanto;
use Concerto\database\SeibanTanto;
use Concerto\database\SeibanTantoData;
use Concerto\standard\Session;
use Concerto\FiscalYear;

class CyunyuInfDispModel
{
    /**
    *   object
    *
    *   @val object
    */
    private $pdo;
    private $cyubanInf;
    private $cyubanInfData;
    private $cyunyuInf;
    private $cyunyuInfData;
    private $kobanInf;
    private $kobanInfData;
    private $mstBumon;
    private $mstBumonData;
    private $mstTanto;
    private $seibanTanto;
    private $seibanTantoData;
    
    /**
    *   __construct
    *
    *   @param PDO $pdo
    *   @param CyubanInf $cyubanInf
    *   @param CyubanInfData $cyubanInfData
    *   @param CyunyuInf $cyunyuInf
    *   @param CyunyuInfData $cyunyuInfData
    *   @param KobanInf $kobanInf
    *   @param KobanInfData $kobanInfData
    *   @param MstBumon $mstBumon
    *   @param MstBumonData $mstBumonData
    *   @param MstTanto $mstTanto
    *   @param SeibanTanto $seibanTanto
    *   @param SeibanTantoData $seibanTantoData
    */
    public function __construct(
        PDO $pdo,
        CyubanInf $cyubanInf,
        CyubanInfData $cyubanInfData,
        CyunyuInf $cyunyuInf,
        CyunyuInfData $cyunyuInfData,
        KobanInf $kobanInf,
        KobanInfData $kobanInfData,
        MstBumon $mstBumon,
        MstBumonData $mstBumonData,
        MstTanto $mstTanto,
        SeibanTanto $seibanTanto,
        SeibanTantoData $seibanTantoData
    ) {
        $this->pdo = $pdo;
        $this->cyubanInf = $cyubanInf;
        $this->cyubanInfData = $cyubanInfData;
        $this->cyunyuInf = $cyunyuInf;
        $this->cyunyuInfData = $cyunyuInfData;
        $this->kobanInf = $kobanInf;
        $this->kobanInfData = $kobanInfData;
        $this->mstBumon = $mstBumon;
        $this->mstBumonData = $mstBumonData;
        $this->mstTanto = $mstTanto;
        $this->seibanTanto = $seibanTanto;
        $this->seibanTantoData = $seibanTantoData;
    }
    
    /**
    *   注番リスト
    *
    *   @param string $no_cyu 注番
    *   @return array
    */
    public function getCyubanList($no_cyu)
    {
        $cyubanInfData = clone $this->cyubanInfData;
        $cyubanInfData->no_cyu = $no_cyu;
        $result = $this->cyubanInf->select($cyubanInfData);
        
        if (count($result) > 0) {
            $obj = $result[0];
            return $obj->toArray();
        }
        return [];
    }
    
    /**
    *   項番リスト
    *
    *   @param string $no_cyu 注番
    *   @param string $no_ko 項番
    *   @return array
    */
    public function getKobanList($no_cyu, $no_ko)
    {
        $kobanInfData = clone $this->kobanInfData;
        $kobanInfData->no_cyu = $no_cyu;
        $kobanInfData->no_ko = $no_ko;
        $result = $this->kobanInf->select($kobanInfData);
        
        if (count($result) > 0) {
            $obj = $result[0];
            return $obj->toArray();
        }
        return [];
    }
    
    /**
    *   項番集計リスト
    *
    *   @param string $no_cyu 注番
    *   @param string $no_ko 項番
    *   @return array
    */
    public function getKobanAggregateList($no_cyu, $no_ko)
    {
        return $this->kobanInf->getAggregate(null, null, null, $no_cyu, $no_ko);
    }
    
    /**
    *   注入リスト
    *
    *   @param string $no_cyu 注番
    *   @param string $no_ko 項番
    *   @param string $flg 0:計画/1:実績
    *   @return array
    */
    public function getCyunyuList($no_cyu, $no_ko, $flg)
    {
        $sql = "
            SELECT A.*
                , B. noki_day AS dt_noki 
            FROM public.cyunyu_inf A 
            LEFT JOIN
                (SELECT *  
                FROM symphony.tsal0050 
                WHERE eda_no = '00' 
                    AND ko_no = '00' 
                ) B 
                ON B.irai_no = A.no_cyumon 
            WHERE A.no_cyu = :no_cyu 
                AND A.no_ko = :no_ko 
                AND A.kb_cyunyu = :flg 
            ORDER BY A.dt_kanjyo, A.dt_cyunyu, A.cd_genka_yoso
                , A.cd_tanto, A.no_seq
        ";
            
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':no_cyu', $no_cyu, PDO::PARAM_STR);
        $stmt->bindValue(':no_ko', $no_ko, PDO::PARAM_STR);
        $stmt->bindValue(':flg', $flg, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
    *   年度リスト
    *
    *   @param string $no_cyu 注番
    *   @param string $no_ko 項番
    *   @return array [[kb_nendo, nm_nendo]]
    */
    public function getNendoList($no_cyu, $no_ko)
    {
        $kobanInfData = clone $this->kobanInfData;
        $kobanInfData->no_cyu = $no_cyu;
        $kobanInfData->no_ko = $no_ko;
        $result = $this->kobanInf->select($kobanInfData);
        
        if (count($result) > 0) {
            $obj = $result[0];
            $nendo = empty($obj->kb_nendo)?
                FiscalYear::getPresentNendo():$obj->kb_nendo;
        } else {
            $nendo = FiscalYear::getPresentNendo();
        }
        
        $nendo_end = FiscalYear::getPreviousNendo(
            FiscalYear::getPresentNendo()
        );
        
        $nendo_list = [];
        $cnt = 0;   //無限ループ防止
    
        do {
            $nendo_list[] = [
                'kb_nendo' => $nendo,
                'nm_nendo' => FiscalYear::nendoCodeToZn($nendo)
            ];
            
            $nendo = FiscalYear::getPreviousNendo($nendo);
            if (($cnt++) > 40) {
                break;
            }
        } while ($nendo >= $nendo_end);
        return $nendo_list;
    }
    
    /**
    *   入力欄年月リスト
    *
    *   @param string $kb_nendo 年度
    *   @return array
    */
    public function getYYYYMMList($kb_nendo)
    {
        return array_merge(
            FiscalYear::getNendoyyyymm($kb_nendo),
            FiscalYear::getNendoyyyymm(FiscalYear::getNextNendo($kb_nendo))
        );
    }
    
    /**
    *   原価要素リスト
    *
    *   @return array [cd_genka_yoso => nm_genka_yoso]
    */
    public function getGenkaYosoList()
    {
        $cyunyuInfData = clone $this->cyunyuInfData;
        return $cyunyuInfData->getCdGenkaYoso();
    }
    
    /**
    *   部門リスト
    *
    *   @return array [[cd_bumon, nm_bumon]]
    */
    public function getBumonList()
    {
        $mstBumonData = clone $this->mstBumonData;
        $mstBumonData->fg_cost = '1';
        $mstBumonData->kb_nendo = FiscalYear::getPresentNendo();
        $result = $this->mstBumon->getBumonList($mstBumonData, 'bumon_code');
        
        $bumon_list = [];
        
        if (count($result) > 0) {
            foreach ($result as $obj) {
                $bumon_list[] = [
                    'cd_bumon' => $obj->bumon_code,
                    'nm_bumon' => $obj->bumon_name
                ];
            }
        }
        return $bumon_list;
    }
    
    /**
    *   担当リスト
    *
    *   @param string $cd_bumon 部門コード
    *   @return array [[cd_tanto, nm_tanto]]
    */
    public function getTantoList($cd_bumon)
    {
        return $this->mstTanto->getTantoListSpecifyBumon($cd_bumon);
    }
    
    /**
    *   注入データ
    *
    *   @param string $no_cyu 注番
    *   @param string $no_ko 項番
    *   @param string $no_seq 0:計画/1:実績
    *   @return array
    */
    public function getCyunyuData($no_cyu, $no_ko, $no_seq)
    {
        $cyunyuInfData = clone $this->cyunyuInfData;
        $cyunyuInfData->no_cyu = $no_cyu;
        $cyunyuInfData->no_ko  = $no_ko;
        $cyunyuInfData->no_seq = $no_seq;
        $result = $this->cyunyuInf->select(
            $cyunyuInfData,
            'dt_kanjyo, dt_cyunyu, cd_genka_yoso, no_seq'
        );
        
        if (count($result) > 0) {
            $items = [];
            
            foreach ($result as $obj) {
                $items[] = $obj->toArray();
            }
            return $items;
        }
        return [];
    }
    
    /**
    *   担当リスト
    *
    *   @return array
    */
    public function getNmTantoList()
    {
        $result = $this->cyunyuInf->getNmTantoList(
            FiscalYear::getPreviousNendo(FiscalYear::getPresentNendo()),
            FiscalYear::getPresentNendo()
        );
        
        if (count($result) > 0) {
            return array_map(
                function ($list) {
                    return $list['nm_tanto'];
                },
                $result
            );
        }
        return [];
    }
    
    /**
    *   商品リスト
    *
    *   @return array
    */
    public function getNmSyohinList()
    {
        $result =  $this->cyunyuInf->getNmSyohinList(
            FiscalYear::getPreviousNendo(FiscalYear::getPresentNendo()),
            FiscalYear::getPresentNendo()
        );
        
        if (count($result) > 0) {
            return array_map(
                function ($list) {
                    return $list['nm_syohin'];
                },
                $result
            );
        }
        return [];
    }
}
