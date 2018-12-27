<?php
/**
*   CyubanSonekiChart3b
*
*   @version 170913
*/
namespace seiban_kanri2\model\chart;

use seiban_kanri2\model\chart\CyubanSonekiChart3;

class CyubanSonekiChart3b extends CyubanSonekiChart3
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
                'drawable' => ['Values5', 'Values6', 'Values7',],
                'type' => 'StackedBarChart',
                'format' => [
                    'Interleave' => 4
                ],
            ],
        ],
    ];
}
