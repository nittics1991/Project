<?php
/**
*   CyubanSonekiChart1
*
*   @version 171005
*/
namespace seiban_kanri2\model\chart;

use seiban_kanri2\model\chart\ChartDefinition;
use Concerto\standard\ArrayUtil;
use Concerto\FiscalYear;

class CyubanSonekiChart1 extends ChartDefinition
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
                ['Values1', '予算'],
                ['Values2', 'TOV'],
                ['Values3', '予算(累積)'],
                ['Values4', 'TOV(累積)'],
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
            'text' => 'TOV',
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
        
        $keikaku_list = $this->model->getBudgetKeikakuMonthList(
            $kb_nendo,
            $cd_bumon
        );
        
        if (empty($keikaku_list)) {
            $data['Values1'] = [0, 0, 0, 0, 0, 0];
        } else {
            foreach ($keikaku_list as $list) {
                $data['Values1'][] = round($list['yn_yosan'] / 1000000);
            }
        }
        
        $tov_list = $this->model->getBudgetKobanMonthList(
            $kb_nendo,
            $cd_bumon
        );
        
        if (empty($tov_list)) {
            $data['Values2'] = [0, 0, 0, 0, 0, 0];
        } else {
            foreach ($tov_list as $list) {
                $data['Values2'][] = round($list['yn_tov'] / 1000000);
            }
        }
        
        $data['Values3'] = ArrayUtil::stepwise(
            $data['Values1'],
            function ($step, $val) {
                return $step + $val;
            }
        );
        
        $data['Values4'] = ArrayUtil::stepwise(
            $data['Values2'],
            function ($step, $val) {
                return $step + $val;
            }
        );
        
        $this->setPoints($data);
    }
}
