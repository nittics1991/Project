<?php
/**
*   CyubanSonekiChart3
*
*   @version 171017
*/
namespace seiban_kanri2\model\chart;

use seiban_kanri2\model\chart\ChartDefinition;
use Concerto\standard\ArrayUtil;
use Concerto\FiscalYear;

class CyubanSonekiChart3 extends ChartDefinition
{
    /**
    *   calcData
    *
    *   @param string $kb_nendo
    *   @param string $cd_bumon
    **/
    public function calcData($kb_nendo, $cd_bumon)
    {
        $data['Axis'] = FiscalYear::getNendoyyyymm($kb_nendo);
        
        foreach ($data['Axis'] as $val) {
            $data['Values1'][$val] = 0;
            $data['Values2'][$val] = 0;
            $data['Values3'][$val] = 0;
            //$data['Values4'][$val] = 0;
            $data['Values5'][$val] = 0;
            $data['Values6'][$val] = 0;
            $data['Values7'][$val] = 0;
            //$data['Values8'][$val] = 0;
        }
        
        $cyunyu_plist = $this->model->getBudgetCyunyuMonthList(
            $kb_nendo,
            $cd_bumon,
            '2'
        );
        
        foreach ($cyunyu_plist as $list) {
            if (!empty($list)) {
                $data['Values1'][$list['dt_kanjyo']] =
                    round($list['yn_cyokka'] / 1000000);
                $data['Values2'][$list['dt_kanjyo']] =
                    round($list['yn_cyokuzai'] / 1000000);
                $data['Values3'][$list['dt_kanjyo']] =
                    round($list['yn_etc'] / 1000000);
                //$data['Values4'][$list['dt_kanjyo']] =
                    // round($list['yn_ryohi'] / 1000000);
            }
        }
        
        ksort($data['Values1']);
        ksort($data['Values2']);
        ksort($data['Values3']);
        // ksort($data['Values4']);
        $data['Values1'] = array_values($data['Values1']);
        $data['Values2'] = array_values($data['Values2']);
        $data['Values3'] = array_values($data['Values3']);
        // $data['Values4'] = array_values($data['Values4']);
        
        $cyunyu_rlist = $this->model->getBudgetCyunyuMonthList(
            $kb_nendo,
            $cd_bumon,
            '1'
        );
        
        foreach ($cyunyu_rlist as $list) {
            if (!empty($list)) {
                $data['Values5'][$list['dt_kanjyo']] =
                    round($list['yn_cyokka'] / 1000000);
                $data['Values6'][$list['dt_kanjyo']] =
                    round($list['yn_cyokuzai'] / 1000000);
                $data['Values7'][$list['dt_kanjyo']] =
                    round($list['yn_etc'] / 1000000);
                //$data['Values8'][$list['dt_kanjyo']] =
                    // round($list['yn_ryohi'] / 1000000);
            }
        }
        
        ksort($data['Values5']);
        ksort($data['Values6']);
        ksort($data['Values7']);
        // ksort($data['Values8']);
        $data['Values5'] = array_values($data['Values5']);
        $data['Values6'] = array_values($data['Values6']);
        $data['Values7'] = array_values($data['Values7']);
        // $data['Values8'] = array_values($data['Values8']);
        
        $this->setPoints($data);
        $this->setScaleMax($data);
    }
    
    /**
    *   setScaleMax
    *
    *   @param array $data
    **/
    protected function setScaleMax(array $data)
    {
        $container['p'] = [$data['Values1'], $data['Values2'], $data['Values3']];
        $container['r'] = [$data['Values5'], $data['Values6'], $data['Values7']];
        $max = 0;
        
        foreach ($container as $target) {
            $transverse = ArrayUtil::transverse($target);
            foreach ($transverse as $list) {
                $max = max($max, array_sum($list));
            }
        }
        $this->container['scale']['format']['ManualScale'][0]['Max'] = $max;
    }
}
