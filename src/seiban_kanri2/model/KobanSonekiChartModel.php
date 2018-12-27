<?php
/**
*   FacadeModel
*
*   @version 171012
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
use seiban_kanri2\model\kobanSonekiDispCyubanModel;

class KobanSonekiChartModel
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
    private $kobanSonekiDispCyubanModel;
    
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
    *   @param KobanSonekiDispCyubanModel $kobanSonekiDispCyubanModel
    */
    public function __construct(
        PDO $pdo,
        CyubanInf $cyubanInf,
        CyubanInfData $cyubanInfData,
        CyunyuInf $cyunyuInf,
        CyunyuInfData $cyunyuInfData,
        KobanInf $kobanInf,
        KobanInfData $kobanInfData,
        KobanSonekiDispCyubanModel $kobanSonekiDispCyubanModel
    ) {
        $this->pdo = $pdo;
        $this->cyubanInf = $cyubanInf;
        $this->cyubanInfData = $cyubanInfData;
        $this->cyunyuInf = $cyunyuInf;
        $this->cyunyuInfData = $cyunyuInfData;
        $this->kobanInf = $kobanInf;
        $this->kobanInfData = $kobanInfData;
        $this->kobanSonekiDispCyubanModel = $kobanSonekiDispCyubanModel;
    }
    
    /**
    *   製番期間取得
    *
    *   @param string $no_cyu 注番
    *   @param string $no_ko 項番
    *   @return array 期間 ['start', 'end']
    **/
    public function getSeibanDtStartEnd($no_cyu, $no_ko = null)
    {
        $cyubanInfData = clone $this->cyubanInfData;
        $cyubanInfData->no_cyu = $no_cyu;
        $result = $this->cyubanInf->select($cyubanInfData);
        
        if (count($result) == 0) {
            return array('start' => '200001', 'end' => '200001');
        }
        
        $dt_end = empty($result[0]['dt_puriage'])?
            '200001':$result[0]['dt_puriage'];
        
        if (!empty($result[0]['dt_hakkou'])) {
            $dt_start = mb_substr($result[0]['dt_hakkou'], 0, 6);
        } else {
            $dt_start = $dt_end;
        }
        
        if (!is_null($no_ko)) {
            $kobanInfData = clone $this->kobanInfData;
            $kobanInfData->no_cyu = $no_cyu;
            $kobanInfData->no_ko = $no_ko;
            $result2 = $this->kobanInf->select($kobanInfData);
            
            if (count($result2) > 0) {
                if (!empty($result2[0]['dt_pkansei'])) {
                    $dt_end = mb_substr($result2[0]['dt_pkansei'], 0, 6);
                }
            }
        }
        
        $cyunyuInfData = clone $this->cyunyuInfData;
        $cyunyuInfData->no_cyu = $no_cyu;
        
        if (!empty($no_ko)) {
            $cyunyuInfData->no_ko = $no_ko;
        }
        
        $result3 = $this->cyunyuInf->select($cyunyuInfData, 'dt_kanjyo');
        
        if (count($result3) > 0) {
            $start = (empty($result3[0]['dt_kanjyo']))?
                $dt_start:mb_substr($result3[0]['dt_kanjyo'], 0, 6);
            
            if ($start < $dt_start) {
                $dt_start = $start;
            }
            
            $end = empty($result3[count($result3) - 1]['dt_kanjyo'])?
                $dt_end:
                mb_substr($result3[count($result3) - 1]['dt_kanjyo'], 0, 6);
            
            if ($end > $dt_end) {
                $dt_end = $end;
            }
        }
        return compact('dt_start', 'dt_end');
    }
    
    /**
    *   月別注入リスト
    *
    *   @param string $no_cyu 注番
    *   @param string $no_ko 項番
    *   @param string $kb_cyunyu
    *   @return array
    */
    public function getBudgetCyunyuMonthList($no_cyu, $no_ko, $kb_cyunyu)
    {
        return $this->cyunyuInf->getCyubanMonAggregate(
            $no_cyu,
            $no_ko,
            $kb_cyunyu
        );
    }
    
    /**
    *   {inherit}
    **/
    public function getKobanAggregateList(
        $no_cyu,
        $cd_bumon = null,
        $no_ko = null
    ) {
        return $this->kobanSonekiDispCyubanModel->getKobanAggregateList(
            $no_cyu,
            $cd_bumon,
            $no_ko
        );
    }
    
    /**
    *   {inherit}
    **/
    public function getKobanList($no_cyu)
    {
        return $this->kobanSonekiDispCyubanModel->getKobanList($no_cyu);
    }
}
