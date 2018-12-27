<?php
/**
*   CyubanSonekiChart3a
*
*   @version 171006
*/
namespace seiban_kanri2\model\chart;

use seiban_kanri2\model\chart\CyubanSonekiChart3;

class CyubanSonekiChart3a extends CyubanSonekiChart3
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
                //['Values4', 0],
                ['Values5', 0],
                ['Values6', 0],
                ['Values7', 0],
                //['Values8', 0],
            ],
            'AxisUnit' => [
                [0, 'M'],
            ],
            'SerieDescription' => [
                ['Values1', '直課費(Y)'],
                ['Values2', '直材費(Y)'],
                ['Values3', '経費(Y)'],
                //['Values4', '旅費(Y)'],
                ['Values5', '直課費(R)'],
                ['Values6', '直材費(R)'],
                ['Values7', '経費(R)'],
                //['Values8', '旅費(R)'],
            ],
        ],
        'scale' => [
            'format' => [
                'Mode'=>SCALE_MODE_MANUAL,
                'ManualScale' => [
                    0 => ['Min' => 0, 'Max' => 0],
                ],
            ],
        ],
        'charts' => [
            [
                'drawable' => ['Values1', 'Values2', 'Values3',],
                'type' => 'StackedBarChart',
                'format' => [
                    'Interleave' => 4
                ],
            ],
        ],
        'legend' => [
            'drawable' => [
                'Values1', 'Values2', 'Values3', 'Values5', 'Values5', 'Values6', 'Values7'
            ],
        ],
        'title' => [
            'text' => '注入',
        ],
    ];
}
