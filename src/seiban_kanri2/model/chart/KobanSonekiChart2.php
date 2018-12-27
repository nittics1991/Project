<?php
/**
*   KobanSonekiChart2
*
*   @version 170914
*/
namespace seiban_kanri2\model\chart;

use seiban_kanri2\model\chart\ChartDefinition;
use Concerto\DateTimeUtil;
use Concerto\standard\ArrayUtil;

class KobanSonekiChart2 extends ChartDefinition
{
    /**
    *   calcData
    *
    *   @param string $no_cyu
    *   @param string $no_ko
    **/
    public function calcData($no_cyu, $no_ko)
    {
        $this->container['title']['text'] = "注入内訳({$no_cyu}{$no_ko})";
        
        $period = $this->model->getSeibanDtStartEnd($no_cyu, $no_ko);
        
        $min = empty($period['dt_start'])? '200001':$period['dt_start'];
        $max = empty($period['dt_end'])? '200001':$period['dt_end'];
        
        try {
            $data['Axis'] = DateTimeUtil::getIntervalYYYYMM(
                $min . '01',
                $max . '28'
            );
        } catch (Exception $e) {
            return;
        }
        
        $cyunyu_plist = $this->model->getBudgetCyunyuMonthList(
            $no_cyu,
            $no_ko,
            '0'
        );
        $cyunyu_rlist = $this->model->getBudgetCyunyuMonthList(
            $no_cyu,
            $no_ko,
            '1'
        );
        
        $template = [
            'tm_cyokka' => 0,
            'yn_cyokka' => 0,
            'yn_cyokuzai' => 0,
            'yn_ryohi' => 0,
            'yn_etc' => 0
        ];
        
        if (empty($cyunyu_plist)) {
            $data['Values1'] = array_fill(0, count($data['Axis']), 0);
            $data['Values2'] = array_fill(0, count($data['Axis']), 0);
            $data['Values3'] = array_fill(0, count($data['Axis']), 0);
        } else {
            $fill = ArrayUtil::toFillBlank(
                $cyunyu_plist,
                'dt_kanjyo',
                $data['Axis'],
                $template
            );
            $order = ArrayUtil::orderBy($fill, ['dt_kanjyo']);
            
            foreach ($order as $list) {
                $data['Values1'][] = round($list['yn_cyokka'] / 1000);
                $data['Values2'][] = round($list['yn_cyokuzai'] / 1000);
                $data['Values3'][] = round($list['yn_etc'] / 1000);
            }
        }
        
        if (empty($cyunyu_rlist)) {
            $data['Values4'] = array_fill(0, count($data['Axis']), 0);
            $data['Values5'] = array_fill(0, count($data['Axis']), 0);
            $data['Values6'] = array_fill(0, count($data['Axis']), 0);
        } else {
            $fill = ArrayUtil::toFillBlank(
                $cyunyu_rlist,
                'dt_kanjyo',
                $data['Axis'],
                $template
            );
            $order = ArrayUtil::orderBy($fill, ['dt_kanjyo']);
            
            foreach ($order as $list) {
                $data['Values4'][] = round($list['yn_cyokka'] / 1000);
                $data['Values5'][] = round($list['yn_cyokuzai'] / 1000);
                $data['Values6'][] = round($list['yn_etc'] / 1000);
            }
        }
        
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
        $container['r'] = [$data['Values4'], $data['Values5'], $data['Values6']];
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
