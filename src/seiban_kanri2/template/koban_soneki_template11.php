var dsOption = {
    fields : [
        {name:'no_cyu'      },
        {name:'no_ko'       },
        {name:'nm_syohin'   },
        {name:'cd_bumon'    },
        {name:'dt_pkansei'  },
        {name:'nm_type'     },
        {name:'yn_tov'      , type:'int'    },
        {name:'yn_cyunyu'   , type:'int'    },
        {name:'yn_soneki'   , type:'int'    },
        {name:'ri_soneki'   , type:'float'  },
        {name:'tm_cyokka'   , type:'float'  },
        {name:'yn_cyokka'   , type:'int'    },
        {name:'yn_cyokuzai' , type:'int'    },
        {name:'yn_etc'      , type:'int'    },
        {name:'yn_ryohi'    , type:'int'    },
        {name:'fg_view'     },
        {name:'yn_ttov'     },
        {name:'yn_tsoneki'  },
        {name:'ri_tsoneki'  , type:'float'  },
        {name:'nm_biko'     },
        {name:'fg_tyousei'      }
    ],
    recordType: 'object'
};

var colsOption = [
    {id:'no_cyu'            ,header:"注番"    ,width: 75,headAlign:"center",align:"center",sortable:false,frozen:true ,
        renderer:function(value, record, colObj, grid, colNo, rowNo) {
            if (record['fg_view']) {
                return '<a href="koban_soneki_disp.php?no_cyu=' + value + '&fg_koban=0" target="_top" title="選択した注番の製番別詳細情報画面を表示します">' + value + '</a>';
            } else {
                return '';
            }
    }},
    {id:'no_ko'             ,header:"項番"    ,width: 60,headAlign:"center",align:"center",sortable:false,frozen:true ,
        renderer:function(value, record, colObj, grid, colNo, rowNo) {
            if (record['fg_view']) {
                return value;
            } else {
                return '';
            }
    }},
    {id:'nm_syohin'         ,header:"品名"    ,width:250,headAlign:"center",align:"left"  ,sortable:false,frozen:true,
        renderer:function(value, record, colObj, grid, colNo, rowNo) {
            if (record['fg_view']) {
                return value;
            } else {
                return '';
            }
    }},
    {id:'dt_pkansei'        ,header:"完成予定",width: 60,headAlign:"center",align:"center",sortable:false,frozen:true,
        renderer:function(value, record, colObj, grid, colNo, rowNo) {
            if (record['fg_view']) {
                return value.substr(0, 6);
            } else {
                return '';
            }
    }},
    {id:'cd_bumon'          ,header:"部門"    ,width: 50,headAlign:"center",align:"center",sortable:false,frozen:true,
        renderer:function(value, record, colObj, grid, colNo, rowNo) {
            if (record['fg_view']) {
                return value;
            } else {
                return '';
            }
    }},
    {id:'nm_type'           ,header:"　"      ,width: 40,headAlign:"center",align:"center",sortable:false,frozen:true ,
        renderer:function(value, record, colObj, grid, colNo, rowNo) {
            if (value == "0") {
                return '<a href="cyunyu_inf_disp.php?no_cyu=' + record['no_cyu'] + '&no_ko=' + record['no_ko'] + '&fg_crt=0" target="_top">計画</a>'
            } else if (value == "1") {
                return '<a href="cyunyu_inf_disp.php?no_cyu=' + record['no_cyu'] + '&no_ko=' + record['no_ko'] + '&fg_crt=1" target="_top">実績</a>'
            } else {
                return '<div>予測</div>'
            }
    }},
    {id:'yn_tov'            ,header:"TOV"     ,width: 80,headAlign:"center",align:"right" ,sortable:true,
        renderer:function(value, record, colObj, grid, colNo, rowNo) {
            return number_format(value); 
    }},
    {id:'yn_cyunyu'         ,header:"注入計"  ,width: 80,headAlign:"center",align:"right" ,sortable:true,
        renderer:function(value, record, colObj, grid, colNo, rowNo) {
            return number_format(value); 
    }},
    {id:'yn_soneki'         ,header:"製番損益",width: 80,headAlign:"center",align:"right" ,sortable:true,
        renderer:function(value, record, colObj, grid, colNo, rowNo) {
            if (parseInt(value) < 0) {
                return '<div style="background-color:#ff0000;">' + number_format(value) + '</div>';
            } else {
                return '<div>' + number_format(value) + '</div>';
            }
    }},
    {id:'ri_soneki'         ,header:"損益率"  ,width: 60,headAlign:"center",align:"right" ,sortable:true,
        renderer:function(value, record, colObj, grid, colNo, rowNo) {
            if (parseInt(value) < 0) {
                return '<div style="background-color:#ff0000;">' + number_format(value, 1) + '</div>';
            } else {
                return '<div>' + number_format(value, 1) + '</div>';
            }
    }},
    {id:'tm_cyokka'         ,header:"直課時間",width: 80,headAlign:"center",align:"right" ,sortable:true,
        renderer:function(value, record, colObj, grid, colNo, rowNo) {
            return number_format(value, 2); 
    }},
    {id:'yn_cyokka'         ,header:"直課金額",width: 80,headAlign:"center",align:"right" ,sortable:true,
        renderer:function(value, record, colObj, grid, colNo, rowNo) {
            return number_format(value); 
    }},
    {id:'yn_cyokuzai'       ,header:"直材費"  ,width: 80,headAlign:"center",align:"right" ,sortable:true,
        renderer:function(value, record, colObj, grid, colNo, rowNo) {
            return number_format(value); 
    }},
    {id:'yn_etc'            ,header:"経費"    ,width: 80,headAlign:"center",align:"right" ,sortable:true,
        renderer:function(value, record, colObj, grid, colNo, rowNo) {
            return number_format(value); 
    }},
    {id:'yn_ryohi'          ,header:"旅費"    ,width: 80,headAlign:"center",align:"right" ,sortable:true,
        renderer:function(value, record, colObj, grid, colNo, rowNo) {
            return number_format(value); 
    }},
    {id:'yn_ttov'           ,header:"調整後TOV"  ,width: 80,headAlign:"center",align:"right" ,sortable:true,
        renderer:function(value, record, colObj, grid, colNo, rowNo) {
            if (record['fg_tyousei']) {
                return (value == '')?   '':number_format(value); 
            } else {
                return;
            }
    }},
    {id:'yn_tsoneki'        ,header:"調整後損益"  ,width: 80,headAlign:"center",align:"right" ,sortable:true,
        renderer:function(value, record, colObj, grid, colNo, rowNo) {
            if (record['fg_tyousei']) {
                return (value == '')?   '':number_format(value); 
            } else {
                return;
            }
    }},
    {id:'ri_tsoneki'        ,header:"調整後損益率",width: 80,headAlign:"center",align:"right" ,sortable:true,
        renderer:function(value, record, colObj, grid, colNo, rowNo) {
            if (record['fg_tyousei']) {
                return (((record['yn_ttov'] == '') && (record['yn_tsoneki'] == '')) || (value == ''))?
                    '':number_format(value, 1); 
            } else {
                return;
            }
    }},
    {id:'nm_biko'           ,header:"備考"    ,width:250,headAlign:"center",align:"left"  ,sortable:false,
        renderer:function(value, record, colObj, grid, colNo, rowNo) {
            if (record['fg_tyousei']) {
                return value;
            } else {
                return '';
            }
    }}
];

<?php require_once('../../../_template/sigmagrid_gridoption.php'); ?>
gridOption.loadURL = 'koban_soneki_grid1.php';
