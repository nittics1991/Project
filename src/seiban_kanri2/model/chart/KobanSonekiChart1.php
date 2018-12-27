<?php
/**
*   KobanSonekiChart1
*
*   @version 170913
*/
namespace seiban_kanri2\model\chart;

use seiban_kanri2\model\chart\ChartDefinition;
use Concerto\DateTimeUtil;
use Concerto\standard\ArrayUtil;

class KobanSonekiChart1 extends ChartDefinition
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
                ['Values3', 0],
            ],
            'AxisUnit' => [
                [0, 'k'],
            ],
            'SerieDescription' => [
                ['Values1', 'TOV'],
                ['Values2', '計画'],
                ['Values3', '予測'],
            ],
            'SerieWeight' => [
                ['Values1', 1],
                ['Values2', 1],
                ['Values3', 1],
            ],
        ],
        'scale' => [
            'format' => [
                'LabelRotation' => 30,
            ],
        ],
        'charts' => [
            [
                'drawable' => ['Values1', 'Values2', 'Values3',],
                'type' => 'LineChart',
            ],
            [
                'drawable' => ['Values1', 'Values2', 'Values3',],
                'type' => 'PlotChart',
            ],
        ],
        'legend' => [
            'drawable' => ['Values1', 'Values2', 'Values3'],
        ],
    ];
    
    /**
    *   calcData
    *
    *   @param string $no_cyu
    *   @param string $no_ko
    **/
    public function calcData($no_cyu, $no_ko)
    {
        $this->container['title']['text'] = "注入計画実績({$no_cyu}{$no_ko})";
        
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
        
        $template = [
            'tm_cyokka' => 0,
            'yn_cyokka' => 0,
            'yn_cyokuzai' => 0,
            'yn_ryohi' => 0,
            'yn_etc' => 0
        ];
        
        $no_ko_tmp = empty($no_ko)? null:$no_ko;
        $koban_list = $this->model->getKobanAggregateList(
            $no_cyu,
            null,
            $no_ko_tmp
        );
        $data['Values1'] = array_fill(
            0,
            count($data['Axis']),
            round($koban_list[0]['yn_tov'] / 1000)
        );
        
        $cyunyu_plist = $this->model->getBudgetCyunyuMonthList(
            $no_cyu,
            $no_ko,
            '0'
        );
        $cyunyu_ylist = $this->model->getBudgetCyunyuMonthList(
            $no_cyu,
            $no_ko,
            '2'
        );
        
        $data['Values2'] = $this->cyunyuToChartList(
            $cyunyu_plist,
            $data['Axis'],
            $template
        );
        
        $data['Values3'] = $this->cyunyuToChartList(
            $cyunyu_ylist,
            $data['Axis'],
            $template
        );
        
        $this->setPoints($data);
    }
    
    /**
    *   注入リスト=>チャートリスト
    *
    *   @param array $cyunyu_list 注入リスト
    *   @rapam array $scale_list 検索キー(横軸)
    *   @rapam array $template 置換行データ
    *   @return array チャートリスト
    **/
    protected function cyunyuToChartList($cyunyu_list, $scale_list, $template)
    {
        if (empty($cyunyu_list)) {
            $data_list = array_fill(0, count($scale_list), 0);
        } else {
            $fill = ArrayUtil::toFillBlank(
                $cyunyu_list,
                'dt_kanjyo',
                $scale_list,
                $template
            );
            $order = ArrayUtil::orderBy($fill, ['dt_kanjyo']);
            unset($monthly);
            
            foreach ($order as $list) {
                $monthly[] = round(
                    (
                        $list['yn_cyokka'] +
                        $list['yn_cyokuzai'] +
                        $list['yn_ryohi'] +
                        $list['yn_etc']
                    ) / 1000
                );
            }
            
            $data_list = ArrayUtil::stepwise($monthly, function ($step, $val) {
                return $step + $val;
            });
        }
        return $data_list;
    }
}
