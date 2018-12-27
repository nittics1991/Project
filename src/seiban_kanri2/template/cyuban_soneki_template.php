<? require_once('../../../_template/header_top.php'); ?>
<? require_once('../../../_template/header_jquery.php'); ?>
<? require_once('../../../_template/header_jquery_ui.php'); ?>
<? require_once('../../../_template/header_sigmagrid_css_std.php'); ?>
<? require_once('../../../_template/header_sigmagrid_js.php'); ?>
<? require_once('../../../_template/header_number_format.php'); ?>
<? require_once('../../../_template/header_date.php'); ?>
<? require_once('../../../_template/header_strtotime.php'); ?>
<? require_once('../../../_template/header_environment.php'); ?>
<? require_once('../../../_template/header_buttonmenu.php'); ?>

<title>製番管理画面</title>

<style>
.th1{
    width:40px;
}

.th2{
    width:80px;
}

#LeftSide{
    float:left;
}

#RightSide{
}
</style>
</head>

<body>

<table class="table-button">
<form name="form2" target="_self" method="GET">
<input type="hidden" name="cd_kisyu" id="_cd_kisyu">
<input type="hidden" name="no_bunya_eigyo" id="_no_bunya_eigyo">
<input type="hidden" name="no_bunya_seizo" id="_no_bunya_seizo">

<tr>
<td id="com_button"></td>
<td>
<select name="cd_bumon" onChange="sel_exec()" title="完成部門を選択します">
<option value=""></option>
<option value="all" <? if ($cd_bumon == 'all') {echo 'selected';} ?>>全て</option>

<? foreach ((array)$bumon_list as $list): ?>
<option value="<?= $list['cd_bumon']; ?>" <? if ($list['cd_bumon'] == $cd_bumon) {echo'selected';} ?>><?= $list['nm_bumon']; ?></option>
<? endforeach; ?>

</select>
</td>

<td>
<select name="kb_nendo" onChange="sel_exec()" title="完成年度を選択します">
<option value=""> </option>

<? foreach ((array)$nendo_list as $list): ?>
<option value="<?= $list['kb_nendo']; ?>" <? if ($list['kb_nendo'] == $kb_nendo) {echo 'selected';} ?>><?= $list['nm_nendo']; ?></option>
<? endforeach; ?>

</select>
</td>

<td>
<? if (!$isPastFiscalYear): ?>
<input type="checkbox" name="chk_nendo_all" id="_chk_nendo_all" value="<?= $chk_nendo_all; ?>" onClick="sel_check(this)" <? if ($chk_nendo_all) {echo 'checked';} ?>>
<label for="_chk_nendo_all" title="チェックで当期以降データを表示条件とします">当期以降</label>
<? endif; ?>

<input type="checkbox" name="chk_kansei" id="_chk_kansei" value="<?= $chk_kansei; ?>" onClick="sel_check(this)" <? if ($chk_kansei) {echo 'checked';} ?>>
<label for="_chk_kansei" title="チェックで売上済データを非表示条件とします">仕掛</label>

<input type="checkbox" name="fg_cyuban" id="_fg_cyuban" value="<?= $fg_cyuban; ?>" onClick="sel_check(this)" <? if ($fg_cyuban) {echo 'checked';} ?>>
<label for="_fg_cyuban" title="チェックで選択部門の項番だけで集計したデータを一覧表の表示条件とします">課内</label>

<input type="checkbox" name="chk_job" id="_chk_job" value="<?= $chk_job; ?>" onClick="check_tanto(this)" <? if ($chk_job) {echo 'checked';} ?>>
<label for="_chk_job" title="チェックでログインユーザ名を表示条件とします">担当</label>

<td><select name="narrow_tanto" onChange="sel_tanto(this)" title="データを絞り込む担当者を選択します">;
<option value="">担当抽出</option>";

<? foreach ((array)$tanto_list as $list): ?>
<option value="<?= $list['cd_tanto']; ?>" <? if ($list['cd_tanto'] == $narrow_tanto) {echo 'selected';} ?>><?= $list['nm_tanto']; ?></option>
<? endforeach; ?>

</select>
</td>
</form>

<td><a class="ui-state-default" href="cyuban_soneki_disp.php" target="_top" title="指定した条件でデータ表示を実行します">検索</a></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td><a class="ui-state-default" href="../../index.php" target="_top" title="メニュー画面に戻ります">戻る</a></td>

<td><a class="ui-state-default" href="cyuban_soneki_chart_make.php?kb_nendo=<?= $kb_nendo; ?>&cd_bumon=<?= $cd_bumon; ?>" target="_blank" title="データをグラフ表示します">分析グラフ</a></td>
<td><a class="ui-state-default" href="cyuban_soneki_excel_read.php?kb_nendo=<?= $kb_nendo; ?>&cd_bumon=<?= $cd_bumon; ?>" target="_top" title="データをEXCELファイルからインポートします">Excel入力</a></td>
<td><a class="ui-state-default" href="cyuban_soneki_excel_make.php?kb_nendo=<?= $kb_nendo; ?>&cd_bumon=<?= $cd_bumon; ?>" target="_blank" title="データをEXCELファイルでダウンロードします">Excel出力</a></td>

<form name="form1" target="_self" method="POST" enctype="multipart/form-data">
<input type="hidden" name="token" value="<?= $this->csrf; ?>">
<input type="hidden" name="act" value="">

<? require_once('../../../_template/environment_button.php'); ?>

</form>

<td><a class="ui-state-default" href="/public/メニュー資料2/cyuban_soneki_disp.pdf" target="_blank" title="操作マニュアルを表示します">？</a></td>
</tr>
</table>


<table class="table-button">
<tr>
<td>
<select name="cd_kisyu" onChange="sel_exec()" title="予算機種を選択します" id="cd_kisyu">
<option value="">予算機種</option>

<? foreach ((array)$kisyu_list as $list): ?>
<option value="<?= $list['cd_kisyu']; ?>" <? if ($list['cd_kisyu'] == $cd_kisyu) {echo'selected';} ?>><?= $list['nm_kisyu']; ?></option>
<? endforeach; ?>

</select>
</td>

<td>
<select name="no_bunya_eigyo" onChange="sel_exec()" title="分野を選択します" id="no_bunya_eigyo">
<option value="">分野</option>

<? foreach ((array)$bunya_eigyo_list as $list): ?>
<option value="<?= $list['no_bunya']; ?>" <? if ($list['no_bunya'] === $no_bunya_eigyo) {echo'selected';} ?>><?= $list['nm_bunya']; ?></option>
<? endforeach; ?>

</select>
</td>

<td>
<select name="no_bunya_seizo" onChange="sel_exec()" title="JOB種別を選択します" id="no_bunya_seizo">
<option value="">JOB種別</option>

<? foreach ((array)$bunya_seizo_list as $list): ?>
<option value="<?= $list['no_bunya']; ?>" <? if ($list['no_bunya'] == $no_bunya_seizo) {echo'selected';} ?>><?= $list['nm_bunya']; ?></option>
<? endforeach; ?>

</select>
</td>

</tr>
</table>


<div id="LeftSide">
<table>
<tr>
<th class="th1"></th>
<th class="th2">SP</th>
<th class="th2">ＴＯＶ</th>
<th class="th2">粗利</th>
<th class="th2">注入計</th>
<th class="th2">製番損益</th>
<th class="th2">損益率</th>
<th class="th2">直課時間</th>
<th class="th2">直課金額</th>
<th class="th2">直材費</th>
<th class="th2">経費</th>
<th class="th2">旅費</th>
</tr>

<tr>
<th>計画</th>
<td class="td-odd-right"><?= number_format($yn_psp); ?></td>
<td class="td-odd-right"><?= number_format($yn_ptov); ?></td>
<td class="td-odd-right"><?= number_format($yn_parari); ?></td>
<td class="td-odd-right"><?= number_format($yn_pcyunyu); ?></td>
<td class="td-odd-right"><?= number_format($yn_psoneki); ?></td>
<td class="td-odd-right"><?= number_format($ri_psoneki, 1); ?></td>
<td class="td-odd-right"><?= number_format($tm_pcyokka, 2); ?></td>
<td class="td-odd-right"><?= number_format($yn_pcyokka); ?></td>
<td class="td-odd-right"><?= number_format($yn_pcyokuzai); ?></td>
<td class="td-odd-right"><?= number_format($yn_petc); ?></td>
<td class="td-odd-right"><?= number_format($yn_pryohi); ?></td>
</tr>

<tr>
<th>予測&nbsp;<a href="/public/メニュー資料2/cyuban_soneki_disp2.pdf" target="_blank" title="予測値の計算式を表示します">?</a></th>
<td class="td-even-right"><?= number_format($yn_ysp); ?></td>
<td class="td-even-right"><?= number_format($yn_ytov); ?></td>
<td class="td-even-right"><?= number_format($yn_yarari); ?></td>
<td class="td-even-right"><?= number_format($yn_ycyunyu); ?></td>
<td class="td-even-right"><?= number_format($yn_ysoneki); ?></td>
<td class="td-even-right"><?= number_format($ri_ysoneki, 1); ?></td>
<td class="td-even-right"><?= number_format($tm_ycyokka, 2); ?></td>
<td class="td-even-right"><?= number_format($yn_ycyokka); ?></td>
<td class="td-even-right"><?= number_format($yn_ycyokuzai); ?></td>
<td class="td-even-right"><?= number_format($yn_yetc); ?></td>
<td class="td-even-right"><?= number_format($yn_yryohi); ?></td>
</tr>

<tr>
<th>実績</th>
<td class="td-odd-right"><?= number_format($yn_rsp); ?></td>
<td class="td-odd-right"><?= number_format($yn_rtov); ?></td>
<td class="td-odd-right"><?= number_format($yn_rarari); ?></td>
<td class="td-odd-right"><?= number_format($yn_rcyunyu); ?></td>
<td class="td-odd-right"><?= number_format($yn_rsoneki); ?></td>
<td class="td-odd-right"><?= number_format($ri_rsoneki, 1); ?></td>
<td class="td-odd-right"><?= number_format($tm_rcyokka, 2); ?></td>
<td class="td-odd-right"><?= number_format($yn_rcyokka); ?></td>
<td class="td-odd-right"><?= number_format($yn_rcyokuzai); ?></td>
<td class="td-odd-right"><?= number_format($yn_retc); ?></td>
<td class="td-odd-right"><?= number_format($yn_rryohi); ?></td>
</tr>

<tr>
<th>Ａ</th>
<td class="td-even-right"><?= number_format($yn_asp); ?></td>
<td class="td-even-right"><?= number_format($yn_atov); ?></td>
<td class="td-even-right"><?= number_format($yn_aarari); ?></td>
<td class="td-even-right"><?= number_format($yn_acyunyu); ?></td>
<td class="td-even-right"><?= number_format($yn_asoneki); ?></td>
<td class="td-even-right"><?= number_format($ri_asoneki, 1); ?></td>
<td class="td-even-right"><?= number_format($tm_acyokka, 2); ?></td>
<td class="td-even-right"><?= number_format($yn_acyokka); ?></td>
<td class="td-even-right"><?= number_format($yn_acyokuzai); ?></td>
<td class="td-even-right"><?= number_format($yn_aetc); ?></td>
<td class="td-even-right"><?= number_format($yn_aryohi); ?></td>
</tr>

<tr>
<th>Ｂ</th>
<td class="td-odd-right"><?= number_format($yn_bsp); ?></td>
<td class="td-odd-right"><?= number_format($yn_btov); ?></td>
<td class="td-odd-right"><?= number_format($yn_barari); ?></td>
<td class="td-odd-right"><?= number_format($yn_bcyunyu); ?></td>
<td class="td-odd-right"><?= number_format($yn_bsoneki); ?></td>
<td class="td-odd-right"><?= number_format($ri_bsoneki, 1); ?></td>
<td class="td-odd-right"><?= number_format($tm_bcyokka, 2); ?></td>
<td class="td-odd-right"><?= number_format($yn_bcyokka); ?></td>
<td class="td-odd-right"><?= number_format($yn_bcyokuzai); ?></td>
<td class="td-odd-right"><?= number_format($yn_betc); ?></td>
<td class="td-odd-right"><?= number_format($yn_bryohi); ?></td>
</tr>

<tr>
<th>Ｃ</th>
<td class="td-even-right"><?= number_format($yn_csp); ?></td>
<td class="td-even-right"><?= number_format($yn_ctov); ?></td>
<td class="td-even-right"><?= number_format($yn_carari); ?></td>
<td class="td-even-right"><?= number_format($yn_ccyunyu); ?></td>
<td class="td-even-right"><?= number_format($yn_csoneki); ?></td>
<td class="td-even-right"><?= number_format($ri_csoneki, 1); ?></td>
<td class="td-even-right"><?= number_format($tm_ccyokka, 2); ?></td>
<td class="td-even-right"><?= number_format($yn_ccyokka); ?></td>
<td class="td-even-right"><?= number_format($yn_ccyokuzai); ?></td>
<td class="td-even-right"><?= number_format($yn_cetc); ?></td>
<td class="td-even-right"><?= number_format($yn_cryohi); ?></td>
</tr>

</table>
</div>

<div id="RightSide">
<table>

<tr>
<th class="th1"></th>
<th class="th2">生産高</th>
<th class="th2">製番損益</th>
<th class="th2">損益率</th>
</tr>

<tr>
<th title="直課計画立案で登録したデータを表示します">予算</th>
<td class="td-odd-right"><?= number_format($yn_yosan); ?></td>
<td class="td-odd-right"><?= number_format($yn_soneki); ?></td>
<td class="td-odd-right"><?= number_format($ri_soneki, 1); ?></td>
</tr>

<tr>
<th>差異</th>
<td class="td-even-right"><?= number_format($yn_yosan_sub); ?></td>
<td class="td-even-right"><?= number_format($yn_soneki_sub); ?></td>
<td class="td-even-right"><?= number_format($ri_soneki_sub, 1); ?></td>
</tr>

<tr>
<th>達成率</th>
<td class="td-odd-right"><?= number_format($yn_yosan_rt, 1); ?></td>
<td class="td-odd-right"><?= number_format($yn_soneki_rt, 1); ?></td>
<td class="td-odd-right"><?= number_format($ri_soneki_rt, 1); ?></td>
</tr>

</table>
</div>

<div id="taskGrid"></div>

</body>
<script>

(function() {
    new Concerto.ButtonMenu('#com_button');
})();

(function() {
    //発番から指定日数経過しても注入無し
    var dt_hakkou = date('Ymd', strtotime(<?= $dt_hakkou_diff_date; ?>));
    //最後の注入から指定日数経過しても注入無し
    var dt_cyunyu = date('Ymd', strtotime(<?= $dt_cyunyu_diff_date; ?>));
    
    var dsOption = {
        fields :[ 
            {name:'no_project'  },
            {name:'nm_project'  },
            {name:'no_cyu'      },
            {name:'approved_by2'},
            {name:'kb_cyumon'   },
            {name:'cd_url'      },
            {name:'nm_syohin'   },
            {name:'nm_setti'    },
            {name:'nm_user'     },
            {name:'no_mitumori'},
            {name:'no_cyumon'   },
            {name:'no_seizo'    },
            {name:'yn_sp'       , type:'int'},
            {name:'yn_tov'      , type:'int'},
            {name:'yn_arari'    , type:'int'},
            {name:'yn_cyunyu'   , type:'int'},
            {name:'yn_soneki'   , type:'int'},
            {name:'ri_soneki'   , type:'float'},
            {name:'tm_cyokka'   , type:'float'},
            {name:'yn_cyokka'   , type:'int'},
            {name:'yn_cyokuzai' , type:'int'},
            {name:'yn_etc'      , type:'int'},
            {name:'yn_ryohi'    , type:'int'},
            {name:'dt_puriage'  },
            {name:'nm_tanto'    }, 
            {name:'nm_sien'     }, 
            {name:'dt_hakkou'   },
            {name:'dt_hatuban'  }, 
            {name:'dt_kakunin'  }, 
            {name:'nm_kakunin'  }, 
            {name:'kb_uriage'   }, 
            {name:'kb_keikaku'  },
            {name:'fg_caution'  },
            {name:'dt_cyunyu'   },
            {name:'yn_ttov'     , type:'int'},
            {name:'yn_tsoneki'  , type:'int'},
            {name:'ri_tsoneki'  , type:'float'},
            {name:'nm_kisyu'},
            {name:'nm_bunya_eigyo'},
            {name:'nm_bunya_seizo'},
            {name:'fg_unapproved'},
        ],
        recordType: 'object'
    };
    
    var colsOption = [
        {id:'nm_project'        ,header:"プロジェクト"    ,width: 75,headAlign:"center"   ,align:"left"   ,sortable:true  ,frozen:true    ,
            renderer:function(value ,record,colObj,grid,colNo,rowNo) {
                if (record['no_project'] == 0) {
                    return;
                } else {
                    return '<a href="koban_soneki_disp.php?no_cyu=' + record['no_project'] + '&fg_project=1" target="_blank" title="製番別詳細情報画面を表示します">' + value + '</a>';
                }
        }},
        {id:'no_cyu'            ,header:"注番"        ,width: 75,headAlign:"center"   ,align:"center" ,sortable:true  ,frozen:true    ,
            renderer:function(value ,record,colObj,grid,colNo,rowNo) {
                //発番から指定日数経過しても注入無しor最後の注入から2ヶ月以上注入無し
                if (
                    ((record['yn_cyunyu'] == 0) && (record['dt_hatuban'] < dt_hakkou) && (record['kb_cyumon'] == '受')) 
                    || 
                    ((record['kb_uriage'] != '1') && (record['dt_cyunyu'] != '') && (record['dt_cyunyu'] < date('Ymd', strtotime('-2 month')))) 
                ){
                    var style = 'style="background-color:#ffff00;"';
                } else {
                    var style = '';
                }
                return '<div ' + style + '><a href="koban_soneki_disp.php?no_cyu=' + value + '" target="_blank" title="製番別詳細情報画面を表示します">' + value + '</a></div>';
        }},
        {id:'fg_unapproved'      ,header:"承認"        ,width: 40,headAlign:"center"   ,align:"center" ,sortable:true ,frozen:true    ,
            renderer:function(value ,record,colObj,grid,colNo,rowNo) {
                if (value) {
                    return '未';
                }
                return '';
        }},
        {id:'kb_cyumon'         ,header:"確度"        ,width: 40,headAlign:"center"   ,align:"center" ,sortable:true ,frozen:true    ,
            renderer:function(value ,record,colObj,grid,colNo,rowNo) {
                return '<a href="koban_soneki_disp.php?no_cyu=' + record['no_cyu'] + '&fg_koban=1" target="_blank" title="製番別詳細情報画面(月別)を表示します">' + value + '</a>';
        }},
        {id:'cd_url'            ,header:"WF"        ,width: 40,headAlign:"center"   ,align:"center" ,sortable:true  ,frozen:true    ,
            renderer:function(value ,record,colObj,grid,colNo,rowNo) {
                if (value == "") {
                    return;
                } else {
                    if (record['fg_caution'] == '1') {
                        var style = 'style="background-color:#ffff00"';
                        var klass = 'class="cd_url"';
                    } else {
                        var style = '';
                        var klass = '';
                    }
                    
                    return '<div ' + style + '><a ' + klass + ' href="' + value + '" target="_blank" title="ワークフロー詳細画面を表示します">要</a></div>';
                }
        }},
        {id:'nm_syohin'         ,header:"品名"        ,width:160,headAlign:"center"   ,align:"left"   ,sortable:true  ,frozen:true    },
        {id:'nm_setti'          ,header:"設置場所"  ,width:160,headAlign:"center"   ,align:"left"   ,sortable:true  ,frozen:true    },
        {id:'nm_user'           ,header:"注文主"   ,width:160,headAlign:"center"   ,align:"left"   ,sortable:true                  },
        {id:'no_mitumori'       ,header:"見積番号"  ,width:100,headAlign:"center"   ,align:"left"   ,sortable:true                  },
        {id:'no_cyumon'     ,header:"客先注文番号"    ,width:100,headAlign:"center"   ,align:"left"   ,sortable:true                  },
        {id:'no_seizo'          ,header:"客先製造番号"    ,width:100,headAlign:"center"   ,align:"left"   ,sortable:true                  },
        {id:'yn_sp'         ,header:"ＳＰ"    ,width: 80,headAlign:"center"   ,align:"right"  ,sortable:true                  ,
            renderer:function(value ,record,colObj,grid,colNo,rowNo) {
                return number_format(value); 
        }},
        {id:'yn_tov'            ,header:"ＴＯＶ"   ,width: 80,headAlign:"center"   ,align:"right"  ,sortable:true                  ,
            renderer:function(value ,record,colObj,grid,colNo,rowNo) {
                return number_format(value); 
        }},
        {id:'yn_arari'          ,header:"粗利"    ,width: 80,headAlign:"center"   ,align:"right"  ,sortable:true                  ,
            renderer:function(value ,record,colObj,grid,colNo,rowNo) {
                return number_format(value); 
        }},
        {id:'yn_cyunyu'         ,header:"注入計"   ,width: 80,headAlign:"center"   ,align:"right"  ,sortable:true                  ,
            renderer:function(value ,record,colObj,grid,colNo,rowNo) {
                return number_format(value); 
        }},
        {id:'yn_soneki'         ,header:"製番損益"  ,width: 80,headAlign:"center"   ,align:"right"  ,sortable:true                  ,
            renderer:function(value ,record,colObj,grid,colNo,rowNo) {
                if (parseInt(value) < 0) {
                    return '<div style="background-color:#ff0000;">' + number_format(value) + '</div>';
                } else {
                    return number_format(value);
                }
        }},
        {id:'ri_soneki'         ,header:"損益率"   ,width: 60,headAlign:"center"   ,align:"right"  ,sortable:true                  ,
            renderer:function(value ,record,colObj,grid,colNo,rowNo) {
                if (parseInt(value) < 0) {
                    return '<div style="background-color:#ff0000;">' + number_format(value, 1) + '</div>';
                } else {
                    return number_format(value, 1);
                }
        }},
        {id:'tm_cyokka'         ,header:"直課時間"  ,width: 80,headAlign:"center"   ,align:"right"  ,sortable:true                  ,
            renderer:function(value ,record,colObj,grid,colNo,rowNo) {
                return number_format(value, 2); 
        }},
        {id:'yn_cyokka'         ,header:"直課金額"  ,width: 80,headAlign:"center"   ,align:"right"  ,sortable:true                  ,
            renderer:function(value ,record,colObj,grid,colNo,rowNo) {
                return number_format(value); 
        }},
        {id:'yn_cyokuzai'       ,header:"直材費"   ,width: 80,headAlign:"center"   ,align:"right"  ,sortable:true                  ,
            renderer:function(value ,record,colObj,grid,colNo,rowNo) {
                return number_format(value); 
        }},
        {id:'yn_etc'            ,header:"経費"        ,width: 80,headAlign:"center"   ,align:"right"  ,sortable:true                  ,
            renderer:function(value ,record,colObj,grid,colNo,rowNo) {
                return number_format(value); 
        }},
        {id:'yn_ryohi'          ,header:"旅費"        ,width: 80,headAlign:"center"   ,align:"right"  ,sortable:true                  ,
            renderer:function(value ,record,colObj,grid,colNo,rowNo) {
                return number_format(value); 
        }},
        {id:'dt_puriage'        ,header:"売上予定"  ,width: 80,headAlign:"center"   ,align:"center" ,sortable:true                  },
        {id:'nm_tanto'          ,header:"発番担当"  ,width: 80,headAlign:"center"   ,align:"center" ,sortable:true                  },
        {id:'nm_sien'           ,header:"営業支援"  ,width: 80,headAlign:"center"   ,align:"center" ,sortable:true                  },
        {id:'dt_hakkou'         ,header:"発番日"   ,width: 80,headAlign:"center"   ,align:"center" ,sortable:true                  },
        {id:'dt_hatuban'        ,header:"発番更新"  ,width: 80,headAlign:"center"   ,align:"center" ,sortable:true                  ,
            renderer:function(value ,record,colObj,grid,colNo,rowNo) {
                //発番から指定日数経過しても注入無し
                if (
                    ((record['yn_cyunyu'] == 0) && (record['dt_hatuban'] < dt_hakkou) && (record['kb_cyumon'] == '受')) 
                ){
                    var style = 'style="background-color:#ffff00;"';
                    var klass = 'class="dt_hatuban" ';
                } else {
                    var style = '';
                        var klass = '';
                }
                return '<div ' + klass + style + '>' + value + '</div>';
        }},
        {id:'dt_kakunin'        ,header:"発番確認"  ,width: 80,headAlign:"center"   ,align:"center" ,sortable:true                  },
        {id:'nm_kakunin'        ,header:"確認者"   ,width: 80,headAlign:"center"   ,align:"center" ,sortable:true                  },
        {id:'dt_cyunyu'         ,header:"最終注入日",width: 80,headAlign:"center"    ,align:"center" ,sortable:true                  ,
            renderer:function(value ,record,colObj,grid,colNo,rowNo) {
                //最後の注入から指定期間以上注入無し
                if (
                    ((record['kb_uriage'] != '1') && (record['dt_cyunyu'] != '') && (record['dt_cyunyu'] < dt_cyunyu)) 
                ){
                    var style = 'style="background-color:#ffff00;"';
                    var klass = 'class="dt_cyunyu" ';
                } else {
                    var style = '';
                    var klass = '';
                }
                return '<div ' + klass + style + '>' + value + '</div>';
        }},
        {id:'yn_ttov'           ,header:"調整後TOV"    ,width: 80,headAlign:"center"   ,align:"right"  ,sortable:true                  ,
            renderer:function(value ,record,colObj,grid,colNo,rowNo) {
                if (value != null) {
                    return number_format(value); 
                }
                return '';
        }},
        {id:'yn_tsoneki'        ,header:"調整後損益" ,width: 80,headAlign:"center"   ,align:"right"  ,sortable:true                  ,
            renderer:function(value ,record,colObj,grid,colNo,rowNo) {
                if (value != null) {
                    return number_format(value); 
                }
                return '';
        }},
        {id:'ri_tsoneki'        ,header:"調整後損益率"    ,width: 60,headAlign:"center"   ,align:"right"  ,sortable:true                  ,
            renderer:function(value ,record,colObj,grid,colNo,rowNo) {
                if (value != null) {
                    return number_format(value, 1); 
                }
                return '';
        }},
        {id:'nm_kisyu', header:"予算機種", width: 150, headAlign:"center", align:"left", sortable:true},
        {id:'nm_bunya_eigyo', header:"分野", width: 80, headAlign:"center", align:"left", sortable:true},
        {id:'nm_bunya_seizo', header:"JOB種別", width: 100, headAlign:"center", align:"left", sortable:true},
    ];
    
    <? require_once('../../../_template/sigmagrid_gridoption.php'); ?>
    gridOption.loadURL = 'cyuban_soneki_grid.php';

    gridOption.toolbarContent = gridOption.toolbarContent.replace('| state ', '') + '| sort | confsave confdel download upload | state';
    
    gridOption.customRowAttribute = function(record, rn, grid)
    {
        now = new Date();
        yyyymm = now.getFullYear() + ("00" + (now.getMonth() + 1)).slice(-2);
        
        //売上予定年月を経過
        if ((parseInt(record['dt_puriage']) < parseInt(yyyymm)) && (record['nm_kakunin'] == '0')) {
            return 'style="background-color:#ffff00"';
        //売上済
        } else if (record['kb_uriage'] != '0') {
                return 'style="background-color:#c0c0c0"';
        //未承認
        } else if (record['fg_unapproved']) {
            return 'style="background-color:#CC6666"';
        //未計画
        } else if (record['kb_keikaku'] == '0') {
            return 'style="background-color:#66ccff"';
        //管理不要
        } else if (record['cd_url'] == '') {
            // return 'style="background-color:#996600"';
        }
    }
    
    gridOption.onCellClick = function(value, record, cell, row, colNo, columnObj, grid) {
        //tooltip
        if (cell.innerHTML.indexOf("cd_url") > 0) {
            this.showCellToolTip(cell, "", "ワークフローのT社への申請が未登録です")
        } else if (cell.innerHTML.indexOf("dt_hatuban") > 0) {
            this.showCellToolTip(cell, "", "発番更新から所定日数経過しても注入がありません")
        } else if (cell.innerHTML.indexOf("dt_cyunyu") > 0) {
            this.showCellToolTip(cell, "", "最後の注入から所定日数経過しても注入がありません")
        } else {
            this.hideCellToolTip();
        }
    }
    
    <? require_once('../../../_template/sigmagrid_height.php'); ?>
    <? require_once('../../../_template/sigmagrid_config.php'); ?>
    <? require_once('../../../_template/sigmagrid_render.php'); ?>


})();

function sel_exec() 
{
    var cd_kisyu = document.getElementById('cd_kisyu').value;
    document.getElementById('_cd_kisyu').value = cd_kisyu;
    
    var no_bunya_eigyo = document.getElementById('no_bunya_eigyo').value;
    document.getElementById('_no_bunya_eigyo').value = no_bunya_eigyo;
    
    var no_bunya_seizo = document.getElementById('no_bunya_seizo').value;
    document.getElementById('_no_bunya_seizo').value = no_bunya_seizo;
    
    document.form2.submit();
}

function sel_check(elm)
{
    elm.value = (elm.checked)? 1:0;
    elm.checked = true;
    document.form2.submit();
}

function check_tanto(elm)
{
	document.form2.narrow_tanto.value = '';
    sel_check(elm);
}

function sel_tanto(elm)
{
    var chk = document.getElementsByName("chk_job");
    chk[0].value = 0;
    chk[0].checked = false;
    sel_check(chk[0]);
}

</script>
</html>
