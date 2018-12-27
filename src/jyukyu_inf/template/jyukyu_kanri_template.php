<?php require_once('../../../_template/header_top.php'); ?>
<?php require_once('../../../_template/header_jquery.php'); ?>
<?php require_once('../../../_template/header_jquery_ui.php'); ?>
<?php require_once('../../../_template/header_sigmagrid_css_std.php'); ?>
<?php require_once('../../../_template/header_sigmagrid_js.php'); ?>
<?php require_once('../../../_template/header_valid_form.php'); ?>
<?php require_once('../../../_template/header_buttonmenu.php'); ?>
<?php require_once('../../../_template/header_environment.php'); ?>

<title>受給品管理画面</title>

<style>

.warn {
    background-color:#ffff00;
}

</style>
</head>

<body>
<?php require_once('../../../_template/splash_window.php'); ?>

<table class="table-button">
<tr>
<td id="com_button"></td>

<form name="form2" target="_self" method="GET">

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
</td>

<td><a class="ui-state-default" href="jyukyu_kanri_disp.php" target="_top" title="指定した条件でデータ表示を実行します">検索</a></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td><a class="ui-state-default" href="../../index.php" target="_top" title="メニュー画面に戻ります">終了</a></td>
<td><a class="ui-state-default" href="jyukyu_kanri_excel_make.php" target="_blank" title="データをEXCELファイルでダウンロードします">Excel出力</a></td>

</form>

<form name="form1" target="_self" method="POST" enctype="multipart/form-data">
<input type="hidden" name="token" value="<?= $this->csrf; ?>">
<input type="hidden" name="act" value="">

<? require_once('../../../_template/environment_button.php'); ?>

</form>

<td><a class="ui-state-default" href="/public/メニュー資料2/jyukyu_kanri_disp.pdf" target="_blank" title="操作マニュアルを表示します">？</a></td>
</tr>
</table>

<div id="taskGrid"></div>

</body>
<?php require_once('../../../_template/jquery_datepicker.php'); ?>
<?php require_once('../../../_template/date_input.php'); ?>

<script>

(function() {
    new Concerto.ButtonMenu('#com_button');
})();

(function() {
    var dsOption = {
        fields :[ 
            {name:'no_jyukyu'},
            {name:'nm_syohin'},
            {name:'nm_model'},
            {name:'no_cyu'},
            {name:'no_cyu_t'},
            {name:'nm_daihyo'},
            {name:'nm_setti'},
            {name:'nm_user'},
            {name:'dt_pjyukyu'},
            {name:'no_psuryo', type:'int'},
            {name:'nm_jyukyu_tanto'},
            {name:'dt_rjyukyu'},
            {name:'no_rsuryo', type:'int'},
            {name:'no_zansu', type:'int'},
            {name:'nm_sts'},
            {name:'nm_uketori_tanto'},
            {name:'dt_uketori'},
            {name:'nm_biko'},
            {name:'fg_warn'},
        ],
        recordType: 'object'
    };
    
    var colsOption = [
        {id:'no_jyukyu', header:"受給品番号", width:80, headAlign:"center", align:"left", sortable:true, frozen:true,
            renderer:function(value, record, colObj, grid, colNo, rowNo) {
                return '<a href="../jyukyu_inf/jyukyu_inf_disp.php?no_cyu=' + record['no_cyu'] + '&no_page=0" title="受給品画面を表示します" target="_blank">' + value + '</a>';
        }},
        {id:'nm_syohin', header:"製品名", width:200, headAlign:"center", align:"left", sortable:true, frozen:true},
        {id:'nm_model', header:"型式", width:200, headAlign:"center", align:"left", sortable:true, frozen:true},
        {id:'no_cyu', header:"注番", width:80, headAlign:"center", align:"left", sortable:true, frozen:false,
            renderer:function(value, record, colObj, grid, colNo, rowNo) {
                return '<a href="../wf_new2/wf_seiban_disp.php?no_cyu=' + value + '" title="ワークフロー詳細画面を表示します" target="_blank">' + value + '</a>';
        }},
        {id:'no_cyu_t', header:"注番(T)", width:120, headAlign:"center", align:"left", sortable:true, frozen:false},
        {id:'nm_daihyo', header:"品名", width:160, headAlign:"center", align:"left", sortable:true, frozen:false},
        {id:'nm_setti', header:"設置場所", width:160, headAlign:"center", align:"left", sortable:true, frozen:false},
        {id:'nm_user', header:"注文主", width:160, headAlign:"center", align:"left", sortable:true, frozen:false},
        {id:'dt_pjyukyu', header:"受給予定日", width:80, headAlign:"center", align:"center", sortable:true, frozen:false},
        {id:'no_psuryo', header:"受給数量", width:60, headAlign:"center", align:"right", sortable:true, frozen:false},
        {id:'nm_jyukyu_tanto', header:"受入担当", width:80, headAlign:"center", align:"left", sortable:true, frozen:false},
        {id:'dt_rjyukyu', header:"受入日", width:80, headAlign:"center", align:"center", sortable:true, frozen:false},
        {id:'no_rsuryo', header:"受入数量", width:60, headAlign:"center", align:"right", sortable:true, frozen:false},
        {id:'no_zansu', header:"受入残数", width:60, headAlign:"center", align:"right", sortable:true, frozen:false,
            renderer:function(value, record, colObj, grid, colNo, rowNo) {
                var cls = record['fg_warn']? 'warn':'';
                return '<div class="' + cls + '">' + value + '</div>';
        }},
        {id:'nm_sts', header:"受取状態", width:80, headAlign:"center", align:"center", sortable:true, frozen:false},
        {id:'nm_uketori_tanto', header:"受取担当", width:80, headAlign:"center", align:"left", sortable:true, frozen:false},
        {id:'dt_uketori', header:"受取日", width:80, headAlign:"center", align:"center", sortable:true, frozen:false},
        {id:'nm_biko', header:"備考", width:400, headAlign:"center", align:"left", sortable:true, frozen:false},
    ];
    
    <?php require_once('../../../_template/sigmagrid_gridoption.php'); ?>
    gridOption.loadURL = 'jyukyu_kanri_grid.php';

    gridOption.toolbarContent = gridOption.toolbarContent.replace('| state ', '') + '| sort | confsave confdel download upload | state';
    
    gridOption.showIndexColumn = true;
    
    <?php require_once('../../../_template/sigmagrid_config.php'); ?>
    <?php require_once('../../../_template/sigmagrid_render.php'); ?>

})();

function sel_exec() 
{
    document.form2.submit();
}

function sel_check(elm)
{
    elm.value = (elm.checked)? 1:0;
    elm.checked = true;
    document.form2.submit();
}

</script>
</html>
