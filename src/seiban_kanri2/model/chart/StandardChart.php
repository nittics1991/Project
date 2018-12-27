<?php
return [
    'palette' => __DIR__ . '\\..\\..\\..\\_config\\chart\\Concerto.color',
    'canvas' => [
        'width' => 1280,
        'height' => 800,
        'rgba' => '#cfcfcfff'
    ],
    'chartArea' => [
        'marginTop' => 50,
        'marginBottom' => 250,
        'marginLeft' => 80,
        'marginRight' => 80,
        'rgba' => '#ffffffff'
    ],
    'scale' => [
        'format' => [
            'Pos' => SCALE_POS_LEFTRIGHT
        ],
        'font' => [
            'FontName'=>'C:/WINDOWS/Fonts/HGRGM.TTC',
            'FontSize' => 14,
        ]
    ],
    'legend' => [
        'options' => [
            20,
            620,
            [
                'FontName'=>'C:/WINDOWS/Fonts/HGRGM.TTC',
                'FontSize' => 14,
            ],
        ]
    ],
    'title' => [
        null,
        20,
        'DUMMY',
        [
            'FontName' => 'C:/WINDOWS/Fonts/HGRGE.TTC',
            'FontSize' => 20,
            'Align' => TEXT_ALIGN_TOPMIDDLE,
        ],
    ],
    'dataTable' => [
        'table' => [
            180,
            600,
            1200,
            790,
        ],
        'cell' => [
            'R' => 255,
            'G' => 255,
            'B' => 255,
        ],
        'font' => [
            'FontName'=>'C:/WINDOWS/Fonts/HGRGM.TTC',
            'FontSize' => 14,
        ],
        'padding' => 4,
    ],
];
