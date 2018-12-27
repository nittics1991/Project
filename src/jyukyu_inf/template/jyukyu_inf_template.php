<?php require_once('../../../_template/header_top.php'); ?>
<?php require_once('../../../_template/header_jquery.php'); ?>
<?php require_once('../../../_template/header_jquery_ui.php'); ?>
<?php require_once('../../../_template/header_sigmagrid_css_std.php'); ?>
<?php require_once('../../../_template/header_sigmagrid_js.php'); ?>
<?php require_once('../../../_template/header_valid_form.php'); ?>
<?php require_once('../../../_template/header_buttonmenu.php'); ?>
<?php require_once('../../../_template/header_input_helper.php'); ?>

<title>受給品画面</title>

<style>

.ui-datepicker {
    z-index:100 !important;
}

.text1 {
    width:200px;
}

.text2 {
    width:80px;
}

.text3 {
    width:720px;
}

.select1 {
    width:200px;
}

.select2 {
    width:100px;
}

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
<td><a class="ui-state-default" href="../wf_new2/wf_seiban_disp.php?no_cyu=<?= $no_cyu; ?>&no_page=<?= $no_page; ?>&no_rev=<?= $no_rev; ?>" target="_top" title="ワークフロー詳細画面に戻ります">終了</a></td>
<td><a class="ui-state-default" href="jyukyu_inf_excel_make.php" target="_blank" title="データをEXCELファイルでダウンロードします">Excel出力</a></td>
<td><a class="ui-state-default" href="/public/メニュー資料2/jyukyu_inf_disp.pdf" target="_blank" title="操作マニュアルを表示します">？</a></td>
</tr>
</table>

<div id="taskGrid"></div>
<br>

<form name="form2" target="_top" method="POST" enctype="multipart/form-data">
<input type="hidden" name="token" value="<?= $this->csrf; ?>">
<input type="hidden" name="no_cyu" value="<?= $no_cyu; ?>">

<table class="table-button table1">
<tr>
<td><input type="hidden" name="act" value="insert"></td>
<td><a class="ui-state-default" href="javascript:void(0);" onClick="act_exec()" title="受給品情報を登録します">保存/実行</a></td>
<td><a class="ui-state-default" href="javascript:void(0);" onClick="cancel_exec()" title="入力データをクリアします">キャンセル</a></td>
</tr>
</table>

<table>
<tr>
<th class="th1 th-title">製品名選択</th>

<td>
<select name="nm_syohin_sel" class="select1" title="製品名を選択します" onChange="sel_syohin(this)">
<option value=""></option>

<?php foreach ((array)$syohin_list as $list) : ?>
<option value="<?= $list['cd_syohin']; ?>"><?= "{$list['nm_syohin']}({$list['nm_model']})"; ?></option>
<?php endforeach; ?>

</select>
</td>

<th class="th1 th-title">型式選択</th>

<td>
<select name="nm_model_sel" class="select1" title="型式を選択します" onChange="sel_syohin(this)">
<option value=""></option>

<?php foreach ((array)$model_list as $list) : ?>
<option value="<?= $list['cd_syohin']; ?>"><?= "{$list['nm_model']}({$list['nm_syohin']})"; ?></option>
<?php endforeach; ?>

</select>
</td>

<th class="th1">受給品番号</th>
<td><input type="text" name="no_jyukyu" class="text2 input-text-readonly" value="" readonly></td>

</tr>
</table>

<table>
<tr>
<th class="th1">製品名</th>
<td><input type="text" name="nm_syohin" class="text1" value="" title="製品名を入力します" required pattern="^[^｡-ﾟ\x00-\x1f\x7f\x20-\x2f\x3a-\x40\x5b-\x60\x7b-\x7e]*$"></td>

<th class="th1">型式</th>
<td><input type="text" name="nm_model" class="text1" value="" title="型式を入力します" required pattern="^[^｡-ﾟ\x00-\x1f\x7f_]*$"></td>

<th class="th1">受給予定日</th>
<td><input type="text" name="dt_pjyukyu" class="text2 datepicker" value="<?= $dt_pjyukyu; ?>" title="受給予定日を入力します" required pattern="20\d{6}$" onChange="date_input(this)"></td>

<th class="th1">受給数量</th>
<td><input type="number" name="no_psuryo" class="text2 input-text-right" value="1" title="受給数量を入力します" required min="1" max="10000"></td>

</tr>
</table>

<table>
<tr>
<th class="th1">備考</th>
<td><input type="text" name="nm_biko" class="text3" value="" title="備考を入力します" pattern="^[^｡-ﾟ\x00-\x1f\x7f\x20-\x2f\x3a-\x40\x5b-\x60\x7b-\x7e]*$"></td>

</tr>
</table>

</form>

<form name="form3" target="_top" method="POST" enctype="multipart/form-data">
<input type="hidden" name="token" value="<?= $this->csrf; ?>">
<input type="hidden" name="no_cyu" value="<?= $no_cyu; ?>">
<input type="hidden" name="no_jyukyu" value="">
<input type="hidden" name="nm_target" value="">
<input type="hidden" name="nm_target_suryo" value="">

<table class="table-button table1">
<tr>
<td><input type="hidden" name="act" value=""></td>
<td><a class="ui-state-default" href="javascript:void(0);" onClick="ukeire_exec()" title="選択した受給品の受入登録します">受入</a></td>
<td><a class="ui-state-default" href="javascript:void(0);" onClick="all_ukeire_exec()" title="チェックボックスでチェックした受給品を全数受入れたとして登録します">一括受入</a></td>
</tr>
</table>

<table>
<tr>
<th class="th1">受入担当</th>

<td>
<select name="cd_jyukyu_tanto" class="select2" title="受入者を選択します" required>
<option value=""></option>

<?php foreach ((array)$tanto_list as $list) : ?>
<option value="<?= $list['cd_tanto']; ?>" <?php if ($list['cd_tanto'] == $cd_jyukyu_tanto) {
    echo 'selected';
               }; ?>><?= $list['nm_tanto']; ?></option>
<?php endforeach; ?>

</select>
</td>

<th class="th1">受入日</th>
<td><input type="text" name="dt_rjyukyu" class="text2 datepicker" value="<?= $dt_rjyukyu; ?>" title="受入日を入力します" required pattern="20\d{6}$" onChange="date_input(this)"></td>

<th class="th1">受入数量</th>
<td><input type="number" name="no_rsuryo" class="text2 input-text-right" value="1" title="受給数量を入力します" required min="1" max="10000"></td>

</tr>
</table>

</form>

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
        {id:'select', isCheckColumn:true, width:30, headAlign:"center", align:"center", sortable:false, frozen:true},
        {id:'no_jyukyu', header:"受給品番号", width:80, headAlign:"center", align:"left", sortable:true, frozen:true,
            renderer:function(value, record, colObj, grid, colNo, rowNo) {
                var json = JSON.stringify(record);
                return '<a href="javascript:void(0);" title="編集欄に表示します" onClick=\'sel_jyukyu(' + json + ')\'>' + value + '</a>';
        }},
        {id:'nm_syohin', header:"製品名", width:200, headAlign:"center", align:"left", sortable:true, frozen:true},
        {id:'nm_model', header:"型式", width:200, headAlign:"center", align:"left", sortable:true, frozen:true},
        {id:'no_cyu', header:"注番", width:80, headAlign:"center", align:"left", sortable:true, frozen:false},
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
    gridOption.loadURL = 'jyukyu_inf_grid.php';

    gridOption.toolbarContent = gridOption.toolbarContent.replace('| state ', '') + '| sort | confsave confdel download upload | state';
    
    gridOption.height = (
        window.innerHeight
        - parseInt(document.getElementById("taskGrid").getBoundingClientRect().top)
        - 200
    );
    
    gridOption.showIndexColumn = true;
    
    <?php require_once('../../../_template/sigmagrid_config.php'); ?>
    <?php require_once('../../../_template/sigmagrid_render.php'); ?>

    taskGridClone = taskGrid;
})();

function sel_syohin(elm) 
{
    document.form2.nm_syohin.value = elm.value.split('_', 2)[0];
    document.form2.nm_model.value = elm.value.split('_', 2)[1];
}

function sel_jyukyu(record) 
{
    var act = document.form2.act;
    
    if (act.value == 'insert') {
        var elm =
            '<input type="radio" name="act" id="radio-1" value="update" checked><label for="radio-1">編集</label>'  +
            '<input type="radio" name="act" id="radio-2" value="delete"><label for="radio-2">削除</label>';
        
        act.insertAdjacentHTML('afterend', elm);
        act.parentNode.removeChild(act);
    }
    
    document.form2.no_jyukyu.value = record['no_jyukyu'];
    document.form2.nm_syohin.value = record['nm_syohin'];
    document.form2.nm_model.value = record['nm_model'];
    document.form2.dt_pjyukyu.value = record['dt_pjyukyu'];
    document.form2.no_psuryo.value = record['no_psuryo'];
    document.form2.nm_biko.value = record['nm_biko'];
    
    document.form3.no_jyukyu.value = record['no_jyukyu'];
    document.form3.no_rsuryo.value = record['no_psuryo'];
}

function cancel_exec() 
{
    var act = document.form2.act;
    
    if (act.value != 'insert') {
        var elm =
            '<input type="hidden" name="act" value="insert">';
        
        var del = document.getElementById('radio-2');
        var td = del.parentNode;
        while (td.firstChild) td.removeChild(td.firstChild);
        td.insertAdjacentHTML('beforeend', elm);
    }
    
    document.form2.no_jyukyu.value = '';
    document.form2.nm_syohin.value = '';
    document.form2.nm_model.value = '';
    
    var today = new Date();
    var y = today.getFullYear();
    var m = ('00' + (today.getMonth()+1)).slice(-2);
    var d = ('00' + (today.getDate())).slice(-2);
    document.form2.dt_pjyukyu.value = y +m  + d;
    
    document.form2.no_psuryo.value = '1';
    document.form2.nm_biko.value = '';
    
    document.form3.dt_rjyukyu.value = y +m  + d;
    document.form3.no_jyukyu.value = '';
    document.form3.no_rsuryo.value = '1';
    
    document.form2.nm_syohin_sel.value = "";
    document.form2.nm_model_sel.value = "";
}

function act_exec()
{
    if (!validFormCom()) {
        alert("使用できない文字を使用しています");
        return;
    }
    
    if (!document.form2.checkValidity()) {
        alert("登録に不正があります");
        return;
    }
    
    splashView();
    document.form2.submit();
}

function ukeire_exec() 
{
    if (!validFormCom()) {
        alert("使用できない文字を使用しています");
        return;
    }
    
    if (!document.form3.checkValidity()) {
        alert("登録に不正があります");
        return;
    }
    
    if (document.form3.no_jyukyu.value == '') {
        alert("受給品が選択されていません");
        return;
    }
    
    splashView();
    document.form3.act.value = 'ukeire';
    document.form3.submit();
}

function all_ukeire_exec() 
{
    if (!confirm("一括全数受入れ登録しますか")) {
        return;
    }
    
    if (!validFormCom()) {
        alert("使用できない文字を使用しています");
        return;
    }
    
    if (!document.form3.checkValidity()) {
        alert("登録に不正があります");
        return;
    }
    
    var records = taskGridClone.getCheckedRecords('select');
    var ckeced_cyumon = [];
    var cyumon_suryo = [];
    var ar = '';
    var ar2 = '';
    
    if ((records != null) && (records.length > 0)) {
        for (var i=0; i<records.length; i++) {
            ckeced_cyumon.push(records[i]['no_jyukyu']);
            cyumon_suryo.push(records[i]['no_psuryo']);
        }
        ar = ckeced_cyumon.join(',');
        ar2 = cyumon_suryo.join(',');
    }
    
    if (ar.length == 0) {
        alert ('チェックボックスが選択されていません。');
        return;
    } else {
        document.form3.nm_target.value = ar;
        document.form3.nm_target_suryo.value = ar2;
    }
    
    splashView();
    document.form3.act.value = 'allukeire';
    document.form3.submit();
}

</script>
</html>
