var dsOption = {
    fields : [
        {name:'no_cyu'          },
        {name:'no_ko'           },
        {name:'no_seq'          },
        {name:'dt_kanjyo'       },
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
    {id:'dt_kanjyo'         ,header:"年月"        ,width: 60, headAlign:"center", align:"center", sortable:true, frozen:true  

<?php if ((!$fg_lock) || (($kengen_sm >= '2') && ($kengen_sm <= '3')) || ($kengen_db == '1')) : ?>
        , renderer:function(value ,record,colObj,grid,colNo,rowNo) {
            return '<a href="cyunyu_inf_disp.php?no_cyu=' + record['no_cyu'] + '&no_ko=' + record['no_ko'] + '&no_seq=' + record['no_seq'] + '" target="_top" title="選択した項目を編集します">' + value + '</a>';
        }
<?php endif; ?>
    },
    {id:'cd_genka_yoso'     ,header:"要素"        ,width: 40, headAlign:"center", align:"center", sortable:true,frozen:true   },
    {id:'nm_tanto'          ,header:"注入先"   ,width:200, headAlign:"center", align:"left"  , sortable:true,frozen:true   },
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
gridOption.loadURL = 'cyunyu_inf_grid1.php';

gridOption.toolbarContent = gridOption.toolbarContent.replace('| state ', '') + '| sort | confsave confdel download upload | state';

if (document.getElementById("entry") != null) {
    var entry_height = parseInt(
        document.getElementById("entry").getBoundingClientRect().top
        + document.getElementById("entry").clientHeight
        )
        + 10;
} else {
    var entry_height = 80;
}

gridOption.height = (
    window.innerHeight
    - parseInt(document.getElementById("taskGrid").getBoundingClientRect().top)
    - entry_height
);
