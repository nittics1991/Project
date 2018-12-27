<? require_once('../../../_template/header_top.php'); ?>
<? require_once('../../../_template/header_jquery.php'); ?>
<? require_once('../../../_template/header_jquery_ui.php'); ?>
<? require_once('../../../_template/header_sigmagrid_css_std.php'); ?>
<? require_once('../../../_template/header_sigmagrid_js.php'); ?>
<? require_once('../../../_template/header_number_format.php'); ?>
<? require_once('../../../_template/header_input_helper.php'); ?>
<? require_once('../../../_template/header_date.php'); ?>
<? require_once('../../../_template/header_sprintf.php'); ?>
<? require_once('../../../_template/header_valid_form.php'); ?>
<? require_once('../../../_template/header_buttonmenu.php'); ?>

<title>製番別注入画面</title>
<style>
#entry {
}

.th1{
    width:40px;
}

.th2{
    width:80px;
}

.th11{
    width:90px;
}

.th12{
    width:110px;
}

.td11{
    width:600px;
}

.text1{
    width:320px;
}
.title{
}

</style>

</head>
<body>
<? require_once('../../../_template/splash_window.php'); ?>

<form name="form1" target="_top" method="POST" enctype="multipart/form-data">
<input type="hidden" name="token" value="<?= $this->csrf; ?>">
<input type="hidden" name="keikaku" value="keikaku">

<table class="table-button">
<tr>
<td id="com_button"></td>

<? if ($fg_crt == '1'): ?>
    <td><a class="ui-state-default" href="koban_soneki_disp.php?no_cyu=<?= $no_cyu; ?>" target="_top" title="製番別詳細情報画面に戻ります">戻る</a></td>
    <td><a class="ui-state-default" href="cyunyu_inf_disp.php?no_cyu=<?= $no_cyu; ?>&no_ko=<?= $no_ko; ?>&fg_crt=0" target="_top" title="計画一覧を表示します">計画表示</a></td>
    <td><a class="ui-state-default" href="/public/メニュー資料/cyunyu_inf_disp_frame11.pdf" target="_blank" title="操作マニュアルを表示します">？</a></td>
<? else: ?>
    <td><a class="ui-state-default" href="koban_soneki_disp.php?no_cyu=<?= $no_cyu; ?>" target="_top" title="製番別詳細情報画面に戻ります">戻る</a></td>
    <td><a class="ui-state-default" href="cyunyu_inf_disp.php?no_cyu=<?= $no_cyu; ?>&no_ko=<?= $no_ko; ?>&fg_crt=1" target="_top" title="実績一覧を表示します">実績表示</a></td>
    
    <? if ((($kengen_sm >= '2') && ($kengen_sm <= '3')) || ($kengen_db == '1')): ?>
        <td>
        <? if (!$fg_lock): ?>
            <a class="ui-state-default" href="#" target="_self" onClick="kakutei_exec(this)" title="計画変更を制限します">計画確定</a>
        <? else: ?>
            <a class="ui-state-default" href="#" target="_self" onClick="kakutei_exec(this)" title="計画変更制限を解除します">計画確定解除</a>
        <? endif; ?>
        </td>
    <? endif; ?>
    
    <td><a class="ui-state-default" href="../operation_hist2/operation_hist_disp.php?cd_table=1&nm_after=<?= $no_seiban; ?>" target="_blank" title="操作履歴を表示します">履歴</a></td>
    <td><a class="ui-state-default" href="/public/メニュー資料2/cyunyu_inf_disp.pdf" target="_blank" title="操作マニュアルを表示します">？</a></td>
<? endif; ?>

</tr>
</table>
</form>


<table>
<tr>
<tr>
<th colspan="2">製番</th>
<td class="td-even-left" colspan="10"><?= $no_cyu.$no_ko; ?></td>
</tr>

<tr>
<th colspan="2">設置場所</th>
<td class="td-even-left" colspan="10"><?= $nm_setti; ?></td>
</tr>

<tr>
<th colspan="2">品名</th>
<td class="td-even-left" colspan="5"><?= $nm_syohin; ?></td>
<td class="td-even-left" colspan="5"><?= $nm_syohin2; ?></td>
</tr>

<tr>
<th colspan="2">客先名称</th>
<td class="td-even-left" colspan="10"><?= $nm_user; ?></td>
</tr>

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
</table>

<div id="taskGrid"></div>

<form name="form2" target="_self" method="POST" enctype="multipart/form-data">
<input type="hidden" name="token" value="<?= $this->csrf; ?>">
<input type="hidden" name="no_cyu" value="<?= $no_cyu; ?>">
<input type="hidden" name="no_ko" value="<?= $no_ko; ?>">
<input type="hidden" name="no_seq" value="<?= $no_seq; ?>">
<!--計画表示-->
<? if ($fg_crt == '0'): ?>
    <table class="table-button" id="entry">
    <tr>
    <td>
    
    <!--新規登録-->
    <? if (empty($no_seq)): ?>
        <input type="hidden" name="act" value="insert">
    <? else: ?>
        <input type="radio" name="act" id="radio1-1" value="update" checked ><label for="radio1-1" required>更新</label>
        <input type="radio" name="act" id="radio1-2" value="delete" ><label for="radio1-2" required>削除</label>
    <? endif; ?>
    
    <? if(!$fg_lock): ?>
        <a class="ui-state-default" href="#" target="_self" onClick="act_exec(this)" title="登録します">保存/実行</a>
        <a class="ui-state-default" href="cyunyu_inf_disp.php" target="_self" title="入力データをクリアします">キャンセル</a>
    <? endif; ?>
    
    </td>
    </tr>
    </table>
    
    <table>
    <tr>
    <!--新規登録-->
    <? if (empty($no_seq)): ?>
        <th class="th11">年度/原価要素</th>
        <td class="td11">
        <select name="kb_nendo" id="kb_nendo" onChange="sel_nendo(this)" title="注入年度を選択します" required>
        <? foreach ($kb_nendo_list as $list): ?>
            <option value="<?= $list['kb_nendo']; ?>" <? if ($list['kb_nendo'] == $kb_nendo) {echo 'selected';} ?>><?= $list['nm_nendo']; ?></option>
        <? endforeach; ?>
        
        </select>
        
        <? $i = 0; ?>
            <? foreach ((array)$genka_yoso_list as $key => $val): ?>
                <input type="radio" name="cd_genka_yoso" id="r<?= $i; ?>" value="<?= $key; ?>" onClick="sel_youso(this)" <? if ($key == $cd_genka_yoso) {echo 'checked';} ?> required>
                <label for="r<?= $i; ?>"><?= $key; ?>:<?= $val; ?></label>
            <? $i++; ?>
        <? endforeach; ?>
        
        </td>
        </tr>
        
        <tr>
        <th class="th11">注入先</th>
        <td class="td11">
        <select name="cd_bumon" id="cd_bumon" onChange="sel_bumon(this)" <? if ($cd_genka_yoso == 'A') {echo 'disabled';} ?> title="注入先原価部門を選択します">
        <option value="">原価部門</option>
        
        <? foreach ((array)$bumon_list as $list): ?>
            <option value="<?= $list['cd_bumon']; ?>" <? if ($list['cd_bumon'] == $cd_bumon) {echo 'selected';} ?>><?= $list['nm_bumon']; ?></option>
        <? endforeach; ?>
        
        </select>
        
        <select name="cd_tanto" id="cd_tanto" <? if ($cd_genka_yoso == 'A') echo 'disabled'; ?> title="注入先担当を選択します">
        <option value="XXXXXXXX">注入先指定</option>
        
        <? foreach ((array)$tanto_list as $list): ?>
            <option value="<?= $list['cd_tanto']; ?>" <? if ($list['cd_tanto'] == $cd_tanto) {echo 'selected';} ?>><?= $list['nm_tanto']; ?></option>
        <? endforeach; ?>
        
        </select>
        
    <!--更新・削除-->
    <? else: ?>
        <th class="th11">年度/原価要素</th>
        <td class="td11">
        <input type="text" name="kb_nendo" class="input-text-readonly" value="<?= $kb_nendo; ?>" readonly required>
        <input type="text" name="cd_genka_yoso" class="input-text-readonly" value="<?= $cd_genka_yoso; ?>" readonly required>
        </td>
        </tr>
        
        <tr>
        <th class="th11">注入先</th>
        <td class="td11">
        <input type="hidden" name="cd_tanto" value="<?= $cd_tanto; ?>">
    <? endif; ?>
    
    <input type="text" name="nm_tanto" id="nm_tanto" value="<?= $nm_tanto; ?>" <? if (($cd_genka_yoso != 'A') || (!empty($no_seq))) {echo 'class="input-text-readonly text11" readonly';} else {echo 'class="text11"';} ?> title="記号は全角文字" pattern="^[^｡-ﾟ\x00-\x1f\x7f\x20-\x2f\x3a-\x40\x5b-\x60\x7b-\x7e]*$">
    </td>
    
    <tr>
    <th class="th11">商品名称</th>
    <td class="td11">
    <input type="text" name="nm_syohin" id="nm_syohin" value="<?= $nm_syohin3; ?>" <? if (!empty($no_seq)) {echo 'class="input-text-readonly td11" readonly';} else {echo 'class="td11"';} ?> title="記号は全角文字" pattern="^[^｡-ﾟ\x00-\x1f\x7f\x20-\x2f\x3a-\x40\x5b-\x60\x7b-\x7e]*$">
    </td>
    
    </tr>
    </table>
    
    <table class="table2">
    <tr>
    <? for ($i = 0; $i < 6; $i++): ?>
        <th class="th12 title"><?= $dt_yyyymm[$i]; ?></th>
    <? endfor; ?>
    
    </tr>
    
    <tr>
    <? for ($i = 0; $i < 6; $i++): ?>
        <td><input type="number" name="num_data[<?= $i; ?>]" class="th12 input-text-right" value="<?= $num_data[$i]; ?>" onChange="cng_data(this)" step="0.01" min="-100000000" max="100000000" required></td>
    <? endfor; ?>
    
    </tr>
    
    <tr>
    <? for ($i = 6; $i < 12; $i++): ?>
        <th class="th12 title"><?= $dt_yyyymm[$i]; ?></th>
    <? endfor; ?>
    
    </tr>
    
    <tr>
    <? for ($i = 6; $i < 12; $i++): ?>
        <td><input type="number" name="num_data[<?= $i; ?>]" class="th12 input-text-right" value="<?= $num_data[$i]; ?>" onChange="cng_data(this)" step="0.01" min="-100000000" max="100000000" required></td>
    <? endfor; ?>
    
    </tr>
    </table>
    
<? endif;?>

</form>
</body>
<script>

(function() {
    new Concerto.ButtonMenu('#com_button');
})();

<?php
if ($fg_crt == '0') {
    require_once('cyunyu_inf_template11.php');
} else {
    require_once('cyunyu_inf_template12.php');
}

?>

<? require_once('../../../_template/sigmagrid_render.php'); ?>

var nm_tanto_list = <?= $nm_tanto_list; ?>;
var nm_syohin_list = <?= $nm_syohin_list; ?>;

$(function() {
    if ($("input[name=act]").val() == 'insert') {
        $("#nm_tanto").autocomplete({
            source:nm_tanto_list
        });
        
        $("#nm_syohin").autocomplete({
            source:nm_syohin_list
        });
    }
});

function kakutei_exec(elm)
{
    document.form1.submit();
}

function act_exec (elm)
{
    if (!validFormCom()) {
        alert("使用できない文字を使用しています");
        return;
    }
    
    if (!$("form[name=form2]")[0].checkValidity()) {
        alert("入力に不正があります");
        return;
    }
    
    var flg = false;
    
    if ($("input[name=act]").val() == 'insert') {
        var checked = $('[name=cd_genka_yoso]:checked').val();
        
        switch (checked) {
            case 'A':
                if (($("#nm_tanto").val().replace(/\s/g, '') == '')
                    && ($("#nm_syohin").val().replace(/\s/g, '') == '')
                ){
                    flg = true;
                }
                break;
            case 'B':
                if (($("#cd_tanto option:selected").val() == 'XXXXXXXX')
                    || ($("#cd_tanto option:selected").val() == '')
                ) {
                    flg = true;
                }
                break;
            case 'C':
                if (($("#nm_tanto").val().replace(/\s/g, '') == '')
                    && ($("#nm_syohin").val().replace(/\s/g, '') == '')
                    && (($("#cd_tanto option:selected").val() == 'XXXXXXXX')
                        || ($("#cd_tanto option:selected").val() == '')
                    )
                ){
                    flg = true;
                }
                break;
        }
    }
    
    var cd_genka_yoso = (checked == null)? $("input[name=cd_genka_yoso]").val():checked;
    
    $("input[name*=num_data]").each(function(index) {
        var val = $(this).val();
        if (!isFinite(val) || (val > 100000000)) {
            flg = true;
            return false;
        }
        
        if (cd_genka_yoso == 'B') {
            if (val > 1000) {
                flg = true;
                return false;
            }
        }
    });
    
    if(flg) {
        alert("入力に不正があります");
        return;
    }
    
    splashView();
    document.form2.submit();
}

function sel_nendo (elm)
{
    var nendo = elm.value;
    
    if ((nendo == null) || (nendo == '')) {
        alert('年度選択異常');
        return;
    }
    
    var yyyy = parseInt(nendo.substr(0, 4));
    var half = nendo.substr(4, 1);
    var list = [];
    
    if (half == 'S') {
        for (var i=10; i<=12; i++) {
            list.push(yyyy + sprintf("%02d", i));
        }
        for (var i=1; i<=9; i++) {
            list.push((yyyy+1) + sprintf("%02d", i));
        }
    } else {
        for (var i=4; i<=12; i++) {
            list.push(yyyy + sprintf("%02d", i));
        }
        for (var i=1; i<=3; i++) {
            list.push((yyyy+1) + sprintf("%02d", i));
        }
    }
    
    $(".title").each(function(index, elem) {
        $(elem).text(list[index]);
    });
}

function sel_youso (elm)
{
    var checked = $('[name=cd_genka_yoso]:checked').val();
    
    switch (checked) {
        case 'A':
            $("#cd_bumon").prop('disabled', true);
            $("#cd_tanto").prop('disabled', true);
            $("#nm_tanto").prop('readonly', false);
            $("#nm_tanto").removeClass('input-text-readonly');
            break;
        case 'B':
            $("#cd_bumon").prop('disabled', false);
            $("#cd_tanto").prop('disabled', false);
            $("#nm_tanto").prop('readonly', true);
            $("#nm_tanto").addClass('input-text-readonly');
            $("#nm_tanto").val('');
            break;
        case 'C':
            $("#cd_bumon").prop('disabled', false);
            $("#cd_tanto").prop('disabled', false);
            $("#nm_tanto").prop('readonly', false);
            $("#nm_tanto").removeClass('input-text-readonly');
            break;
    }
}

function sel_bumon (elm)
{
    $.ajax({
        type: "GET",
        url: "cyunyu_inf_cd_bumon_ajax.php",
        dataType: "json",
        data:{
            'cd_bumon':$("#cd_bumon option:selected").val()
        },
        success: function(json){
            $("#cd_tanto").children().remove().end().append("<option value=\"XXXXXXXX\">注入先指定</option>");
            $.each(json, function(key, val) {
                $("#cd_tanto").append("<option value=\"" + val.cd_tanto + "\">" + val.nm_tanto + "</option>");
            });
        },
        error:function(XMLHttpRequest, textStatus, errorThrown){
            alert("担当読込失敗:" + textStatus);
        }
    });
}

function cng_data (elm)
{
    var val = numberInputHelper(elm.value);
    
    if (val === null) {
        alert("数値を入力してください");
        elm.value = 0;
     } else {
        elm.value = val;
     }
}

</script>
</html>

