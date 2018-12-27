<?php
/**
*   KobanSonekiChart2a
*
*   @version 170914
*/
namespace seiban_kanri2\model\chart;

use seiban_kanri2\model\chart\KobanSonekiChart2;

class KobanSonekiChart2a extends KobanSonekiChart2
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
                ['Values4', 0],
                ['Values5', 0],
                ['Values6', 0],
            ],
            'AxisUnit' => [
                [0, 'k'],
            ],
            'SerieDescription' => [
                ['Values1', '直課費(P)'],
                ['Values2', '直材費(P)'],
                ['Values3', '経費(P)'],
                ['Values4', '直課費(R)'],
                ['Values5', '直材費(R)'],
                ['Values6', '経費(R)'],
            ],
        ],
        'scale' => [
            'format' => [
                'LabelRotation' => 30,
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
                'Values1', 'Values2', 'Values3', 'Values4', 'Values5', 'Values6'
            ],
        ],
    ];
}
