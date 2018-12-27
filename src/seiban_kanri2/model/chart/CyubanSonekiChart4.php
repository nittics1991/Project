<?php
/**
*   CyubanSonekiChart4
*
*   @version 171012
*/
namespace seiban_kanri2\model\chart;

use seiban_kanri2\model\chart\ChartDefinition;
use Concerto\standard\ArrayUtil;
use Concerto\FiscalYear;

class CyubanSonekiChart4 extends ChartDefinition
{
    /**
    *   setting
    *
    *   @val array
    **/
    protected $setting = [
        'dataset' => [
            'Abscissa' => ['Axis'],
            'SerieOnAxis' => [
                ['Values1', 0],
                ['Values2', 0],
                ['Values3', 1],
                ['Values4', 1],
            ],
            'AxisPosition' => [
                [1, AXIS_POSITION_RIGHT],
            ],
            'AxisName' => [
                [1, '累積'],
            ],
            'AxisUnit' => [
                [0, 'M'],
                [1, 'M'],
            ],
            'SerieDescription' => [
                ['Values1', 'TOV'],
                ['Values2', '注入'],
                ['Values3', 'TOV(累積)'],
                ['Values4', '注入(累積)'],
            ],
            'SerieWeight' => [
                ['Values3', 1],
                ['Values4', 1],
            ],
        ],
        'charts' => [
            [
                'drawable' => ['Values1', 'Values2',],
                'type' => 'BarChart',
            ],
            [
                'drawable' => ['Values3', 'Values4',],
                'type' => 'LineChart',
            ],
            [
                'drawable' => ['Values3', 'Values4',],
                'type' => 'PlotChart',
            ],
        ],
        'legend' => [
            'drawable' => ['Values1', 'Values2', 'Values3', 'Values4'],
        ],
        'title' => [
            'text' => 'TOV-注入',
        ],
    ];
    
    /**
    *   calcData
    *
    *   @param string $kb_nendo
    *   @param string $cd_bumon
    **/
    public function calcData($kb_nendo, $cd_bumon)
    {
        $data['Axis'] = FiscalYear::getNendoyyyymm($kb_nendo);
        
        $tov_list = $this->model->getBudgetKobanMonthList(
            $kb_nendo,
            $cd_bumon
        );
        
        if (empty($tov_list)) {
            $data['Values1'] = [0, 0, 0, 0, 0, 0];
        } else {
            foreach ($tov_list as $list) {
                $data['Values1'][] = round($list['yn_tov'] / 1000000);
            }
        }
        
        $data['Values3'] = ArrayUtil::stepwise(
            $data['Values1'],
            function ($step, $val) {
                return $step + $val;
            }
        );
        
        
        $cyunyu_rlist = $this->model->getBudgetCyunyuMonthList(
            $kb_nendo,
            $cd_bumon,
            '2'
        );
        
        foreach ($cyunyu_rlist as $list) {
            if (!empty($list) && in_array($list['dt_kanjyo'], $data['Axis'])) {
                $data['Values2'][$list['dt_kanjyo']] = round((
                    $list['yn_cyokka'] +
                    $list['yn_cyokuzai'] +
                    $list['yn_etc'] +
                    $list['yn_ryohi']
                ) / 1000000);
            }
        }
        
        ksort($data['Values2']);
        $data['Values2'] = array_values($data['Values2']);
        $data['Values4'] = ArrayUtil::stepwise(
            $data['Values2'],
            function ($step, $val) {
                return $step + $val;
            }
        );
        
        $this->setPoints($data);
    }
}
