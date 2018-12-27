<?php
/**
*   Model
*
*   @version 171017
*/
namespace seiban_kanri2\model;

use \PDO;
use Concerto\database\CyokkaMonKeikaku;
use Concerto\database\CyokkaMonKeikakuData;
use Concerto\database\CyunyuInf;
use Concerto\database\KobanInf;
use Concerto\FiscalYear;
use seiban_kanri2\model\CyubanSonekiDispGridModel;

class CyubanSonekiChartModel
{
    /**
    *   object
    *
    *   @val object
    */
    private $pdo;
    private $cyokkaMonKeikaku;
    private $cyokkaMonKeikakuData;
    private $cyunyuInf;
    private $kobanInf;
    private $gridCyuban;
    
    /**
    *   __construct
    *
    *   @param PDO $pdo
    *   @param CyokkaMonKeikaku $cyokkaMonKeikaku
    *   @param CyokkaMonKeikakuData $cyokkaMonKeikakuData
    *   @param CyunyuInf $cyunyuInf
    *   @param KobanInf $kobanInf
    *   @param CyubanSonekiDispGridModel $gridCyuban
    */
    public function __construct(
        PDO $pdo,
        CyokkaMonKeikaku $cyokkaMonKeikaku,
        CyokkaMonKeikakuData $cyokkaMonKeikakuData,
        CyunyuInf $cyunyuInf,
        KobanInf $kobanInf,
        CyubanSonekiDispGridModel $gridCyuban
    ) {
        $this->pdo = $pdo;
        $this->cyokkaMonKeikaku = $cyokkaMonKeikaku;
        $this->cyokkaMonKeikakuData = $cyokkaMonKeikakuData;
        $this->kobanInf = $kobanInf;
        $this->cyunyuInf = $cyunyuInf;
        $this->gridCyuban = $gridCyuban;
    }
    
    /**
    *   月別直課計画リスト
    *
    *   @param string $kb_nendo 年度
    *   @param string $cd_bumon 部門 or all
    *   @return array
    */
    public function getBudgetKeikakuMonthList($kb_nendo, $cd_bumon)
    {
        $cyokkaMonKeikakuData = clone $this->cyokkaMonKeikakuData;
        $cyokkaMonKeikakuData->kb_nendo = $kb_nendo;
        $cyokkaMonKeikakuData->cd_bumon = $cd_bumon;
        $result = $this->cyokkaMonKeikaku->select(
            $cyokkaMonKeikakuData,
            'dt_yyyymm'
        );
        
        if (count($result) == 0) {
            return [];
        }
        
        $items = [];
        $yyyymm = FiscalYear::getNendoyyyymm($kb_nendo);
        
        foreach ($result as $obj) {
            if (in_array($obj->dt_yyyymm, $yyyymm)) {
                $items[] = $obj->toArray();
            }
        }
        return $items;
    }
    
    /**
    *   一覧表示月別集計リスト
    *
    *   @param string $kb_nendo 年度
    *   @param string $cd_bumon 部門 or all
    *   @return array
    */
    public function getBudgetKobanMonthList($kb_nendo, $cd_bumon)
    {
        $result = $this->gridCyuban->getCyubanMonAggregeteList(
            $kb_nendo,
            $cd_bumon
        );
        
        if (count($result) == 0) {
            return [];
        }
        
        $items = [];
        $yyyymm = FiscalYear::getNendoyyyymm($kb_nendo);
        
        foreach ($yyyymm as $val) {
            $items[$val] = null;
        }
        
        foreach ($result as $list) {
            if (in_array($list['dt_puriage'], $yyyymm)) {
                $items[$list['dt_puriage']] = $list;
            }
        }
        return $items;
    }
    
    /**
    *   月別注入リスト
    *
    *   @param string $kb_nendo 年度
    *   @param string $cd_bumon 部門 or all
    *   @param string $kb_keikaku 0:実績/1:計画
    *   @return array
    */
    public function getBudgetCyunyuMonthList($kb_nendo, $cd_bumon, $kb_keikaku)
    {
        $cd_bumon_tmp = ($cd_bumon == 'all')? null:$cd_bumon;
        $yyyymm = FiscalYear::getNendoyyyymm($kb_nendo);
        
        $result = $this->cyunyuInf->getMonAggregate(
            $cd_bumon_tmp,
            $yyyymm[0],
            $yyyymm[5],
            $kb_keikaku
        );
        
        if (count($result) == 0) {
            return [];
        }
        
        $items = [];
        
        foreach ($yyyymm as $val) {
            $items[$val] = null;
        }
        
        foreach ($result as $list) {
            if (in_array($list['dt_kanjyo'], $yyyymm)) {
                $items[$list['dt_kanjyo']] = $list;
            }
        }
        return $items;
    }
}
