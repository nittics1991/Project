<?php
return [
    'palette' => __DIR__ . '\\..\\..\\..\\_config\\chart\\Concerto.color',
    'canvas' => [
        'width' => 1280,
        'height' => 800,
        'rgba' => '#cfcfcf00'
    ],
    'chartArea' => [
        'marginTop' => 50,
        'marginBottom' => 250,
        'marginLeft' => 80,
        'marginRight' => 80,
        'rgba' => '#ffffff00'
    ],
    'scale' => [
        'format' => [
            'Pos' => SCALE_POS_LEFTRIGHT,
                'RemoveXAxis' => true,
                'DrawXLines' => false,
                'DrawYLines' => false,
                'AxisAlpha' => 0,
                'TickAlpha' => 0,
            
        ],
        'font' => [
            'FontName'=>'C:/WINDOWS/Fonts/HGRGM.TTC',
            'FontSize' => 1,
            'Alpha' => 0,
        ]
    ],
    'title' => [
        null,
        20,
        'DUMMY',
        [
            'Alpha' => 0,
        ],
    ],
];
