var dsOption = {
    fields :[ 
        {name:'no_cyu'          },
        {name:'no_ko'           },
        {name:'nm_syohin'       },
        {name:'dt_pkansei'      },
        {name:'cd_bumon'        },
        {name:'cd_genka_yoso'   },
        {name:'cd_tanto'        },
        {name:'nm_tanto'        },
        {name:'no_syukei'       },
        {name:'kb_cyunyu'       }
        
    <?php foreach ((array)$dt_yyyymm as $val) : ?>
        ,{name:'<?= $val; ?>', typr:'float'}
    <?php endforeach; ?>
    ],
    recordType: 'object'
};

var colsOption = [
    {id:'no_cyu'        ,header:"注番"        ,width: 80, headAlign:"center", align:"center" , sortable:false, frozen:true,
        renderer:function(value ,record,colObj,grid,colNo,rowNo) {
            if (record['kb_cyunyu'] == '0') {
                return '<a href="koban_soneki_disp.php?no_cyu=' + value + '&fg_koban=1" target="_top" title="選択した注番の製番別詳細情報画面を表示します">' + value + '</a>';
            } else {
                return '';
            }
    }},
    {id:'no_ko'             ,header:"項番"    ,width: 60, headAlign:"center", align:"center", sortable:false, frozen:true ,
        renderer:function(value, record, colObj, grid, colNo, rowNo) {
            if (record['kb_cyunyu'] == '0') {
                return value;
            } else {
                return '';
            }
    }},
    {id:'nm_syohin'         ,header:"品名"    ,width:200, headAlign:"center", align:"left"  , sortable:false, frozen:true,
        renderer:function(value, record, colObj, grid, colNo, rowNo) {
            if (record['kb_cyunyu'] == '0') {
                return value;
            } else {
                return '';
            }
    }},
    {id:'dt_pkansei'        ,header:"完成予定",width: 60, headAlign:"center", align:"center", sortable:false, frozen:true,
        renderer:function(value, record, colObj, grid, colNo, rowNo) {
            if (record['kb_cyunyu'] == '0') {
                return value.substr(0, 6);
            } else {
                return '';
            }
    }},
    {id:'cd_bumon'          ,header:"部門"    ,width: 50, headAlign:"center", align:"center", sortable:false, frozen:true,
        renderer:function(value, record, colObj, grid, colNo, rowNo) {
            if (record['kb_cyunyu'] == '0') {
                return value;
            } else {
                return '';
            }
    }},
    {id:'kb_cyunyu'         ,header:"　"      ,width: 40, headAlign:"center", align:"center", sortable:false, frozen:true ,
        renderer:function(value, record, colObj, grid, colNo, rowNo) {
            if (record['kb_cyunyu'] == '0') {
                return '<a href="cyunyu_inf_disp.php?no_cyu=' + record['no_cyu'] + '&no_ko=' + record['no_ko'] + '&fg_crt=0" target="_top">計画</a>'
            } else if (value == "1") {
                return '<a href="cyunyu_inf_disp.php?no_cyu=' + record['no_cyu'] + '&no_ko=' + record['no_ko'] + '&fg_crt=1" target="_top">実績</a>'
            } else {
                return '<div>' + value + '</div>'
            }
    }},
    {id:'cd_genka_yoso'     ,header:"要素"    ,width: 40, headAlign:"center", align:"center", sortable:false, frozen:true,
        renderer:function(value, record, colObj, grid, colNo, rowNo) {
            if (record['kb_cyunyu'] == '0') {
                return value;
            } else {
                return '';
            }
    }},
    {id:'nm_tanto'          ,header:"担当"    ,width: 80, headAlign:"center", align:"center", sortable:false, frozen:true,
        renderer:function(value, record, colObj, grid, colNo, rowNo) {
            if (record['kb_cyunyu'] == '0') {
                return value;
            } else {
                return '';
            }
    }},
    {id:'no_syukei'         ,header:"合計"    ,width: 80, headAlign:"center", align:"right", sortable:false, frozen:true, 
        renderer:function(value, record, colObj, grid, colNo, rowNo) {
            return number_format(value);
    }}
    
    <?php foreach ((array)$dt_yyyymm as $val) : ?>
    ,{id:"<?= $val; ?>" ,header:"<?= substr($val, 2, 4); ?>" ,width: 60, headAlign:"center", align:"right" ,sortable:true,
        renderer:function(value, record, colObj, grid, colNo, rowNo) {
            return number_format(value);
    }}
    <?php endforeach; ?>
];

<?php require_once('../../../_template/sigmagrid_gridoption.php'); ?>
gridOption.loadURL = 'koban_soneki_grid2.php';
