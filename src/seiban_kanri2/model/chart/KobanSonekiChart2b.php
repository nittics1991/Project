<?php
/**
*   KobanSonekiChart2b
*
*   @version 170914
*/
namespace seiban_kanri2\model\chart;

use seiban_kanri2\model\chart\KobanSonekiChart2;

class KobanSonekiChart2b extends KobanSonekiChart2
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
                'drawable' => ['Values4', 'Values5', 'Values6',],
                'type' => 'StackedBarChart',
                'format' => [
                    'Interleave' => 4
                ],
            ],
        ],
    ];
}
