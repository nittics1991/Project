today = date('Ymd');

var dsOption = {
    fields : [
        {name:'no_cyu'          },
        {name:'no_ko'           },
        {name:'no_seq'          },
        {name:'dt_kanjyo'       },
        {name:'dt_noki'         },
        {name:'dt_cyunyu'       },
        {name:'no_cyumon'       },
        {name:'cd_genka_yoso'   },
        {name:'nm_tanto'        },
        {name:'nm_syohin'       },
        {name:'tm_cyokka'       , type:'float'  },
        {name:'yn_cyokka'       , type:'int'    },
        {name:'yn_cyokuzai'     , type:'int'    },
        {name:'yn_etc'          , type:'int'    },
        {name:'yn_ryohi'        , type:'int'    }
        
    ],
    recordType: 'object'
};

var colsOption = [
    {id:'dt_kanjyo'         ,header:"年月"        ,width: 60, headAlign:"center", align:"center", sortable:true, frozen:true  },
    {id:'dt_noki'           ,header:"納期"        ,width: 80, headAlign:"center", align:"center", sortable:true, frozen:true  },
    {id:'dt_cyunyu'         ,header:"注入日"   ,width: 80, headAlign:"center", align:"center", sortable:true, frozen:true  },
    {id:'no_cyumon'         ,header:"注文番号"  ,width: 80, headAlign:"center", align:"center", sortable:true, frozen:true  ,
        renderer:function(value ,record,colObj,grid,colNo,rowNo) {
            return '<a href="../tyotatu_inf/tyotatu_komoku_disp.php?no_cyu=' + record['no_cyu'] + '&no_page=0&no_sheet=1 " target="_blank">' + value + '</a>'; 
    }},
    {id:'cd_genka_yoso'     ,header:"要素"        ,width: 40, headAlign:"center", align:"center", sortable:true, frozen:true  },
    {id:'nm_tanto'          ,header:"注入先"   ,width:200, headAlign:"center", align:"left"  , sortable:true, frozen:true  },
    {id:'nm_syohin'         ,header:"商品名称"  ,width:276, headAlign:"center", align:"left"  , sortable:true               },
    {id:'tm_cyokka'         ,header:"直課時間"  ,width: 80, headAlign:"center", align:"right" , sortable:true               ,
        renderer:function(value ,record,colObj,grid,colNo,rowNo) {
            return number_format(value, 2); 
    }},
    {id:'yn_cyokka'         ,header:"直課金額"  ,width: 80, headAlign:"center", align:"right" , sortable:true               ,
        renderer:function(value ,record,colObj,grid,colNo,rowNo) {
            return number_format(value); 
    }},
    {id:'yn_cyokuzai'       ,header:"直材費"   ,width: 80, headAlign:"center", align:"right" , sortable:true               ,
        renderer:function(value ,record,colObj,grid,colNo,rowNo) {
            return number_format(value); 
    }},
    {id:'yn_etc'            ,header:"経費"        ,width: 80, headAlign:"center", align:"right" , sortable:true               ,
        renderer:function(value ,record,colObj,grid,colNo,rowNo) {
            return number_format(value); 
    }},
    {id:'yn_ryohi'          ,header:"旅費"        ,width: 80, headAlign:"center", align:"right" , sortable:true               ,
        renderer:function(value ,record,colObj,grid,colNo,rowNo) {
            return number_format(value); 
    }}
];

<?php require_once('../../../_template/sigmagrid_gridoption.php'); ?>
gridOption.loadURL = 'cyunyu_inf_grid2.php';

gridOption.toolbarContent = gridOption.toolbarContent.replace('| state ', '') + '| sort | confsave confdel download upload | state';

gridOption.customRowAttribute = function(record, rn, grid)
{
    if ((record['dt_cyunyu'] == '') && (record['dt_noki'] < today) && (record['cd_genka_yoso'] == 'A')) {
        return 'style="background-color:#ffff00"';
    }
}

<?php require_once('../../../_template/sigmagrid_height.php'); ?>
