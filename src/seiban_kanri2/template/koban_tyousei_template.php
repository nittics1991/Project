<?php require_once('../../../_template/header_top.php'); ?>
<?php require_once('../../../_template/header_jquery.php'); ?>
<?php require_once('../../../_template/header_jquery_ui.php'); ?>
<?php require_once('../../../_template/header_input_helper.php'); ?>
<?php require_once('../../../_template/header_valid_form.php'); ?>
<?php require_once('../../../_template/header_buttonmenu.php'); ?>

<title>調整値設定画面</title>

<style>
.text1 {
    width:60px;
}

.text2 {
    width:250px;
}

.text3 {
    width:80px;
    text-align:right;
}

.text4 {
    width:250px;
}

</style>

</head>
<body>
<?php require_once('../../../_template/splash_window.php'); ?>

<form name="form1" method="POST" target="_top" enctype="multipart/form-data">
<input type="hidden" name="token" value="<?= $this->csrf; ?>">
<input type="hidden" name="no_cyu" value="<?= $no_cyu; ?>">
<input type="hidden" name="act" value="update">

<table class="table-button">
<tr>
<td id="com_button"></td>
<td>
<a class="ui-state-default" href="#" target="_self" onClick="act_exec(this)" title="調整値を登録・更新します">保存/実行</a>
<a class="ui-state-default" href="koban_soneki_disp.php?no_cyu=<?= $no_cyu; ?>" target="_top" title="製番別詳細画面に戻ります">戻る</a>
<a class="ui-state-default" href="/public/メニュー資料2/koban_tyousei_disp.pdf"   target="_blank" title="操作マニュアルを表示します">？</a>
</td>
</tr>
</table>
<br>

<table>

<tr>
<th>項番</th>
<th>品名</th>
<th>TOV</th>
<th>製番損益</th>
<th>調整後TOV</th>
<th>調整後損益</th>
<th>備考</th>
</tr>

<?php foreach ((array)$tyousei_list as $list) : ?>
<tr>
<td><input type="text" class="text1 input-text-readonly" name="no_ko[]" readonly value="<?= $list['no_ko']; ?>"></td>
<td><input type="text" class="text2 input-text-readonly" name="nm_syohin[]" readonly value="<?= $list['nm_syohin']; ?>"></td>
<td><input type="text" class="text3 input-text-readonly" name="yn_tov[]" readonly value="<?= $list['yn_tov']; ?>"></td>
<td><input type="text" class="text3 input-text-readonly" name="yn_soneki[]" readonly value="<?= $list['yn_soneki']; ?>"></td>
<td><input type="text" class="text3" name="yn_ttov[]" value="<?= $list['yn_ttov']; ?>" pattern="^((\+|\-)?\d+$)|(^\s*$)"></td>
<td><input type="text" class="text3" name="yn_tsoneki[]" value="<?= $list['yn_tsoneki']; ?>" pattern="^((\+|\-)?\d+$)|(^\s*$)"></td>
<td><input type="text" class="text4" name="nm_biko[]" value="<?= $list['nm_biko']; ?>" title="記号は全角文字で" pattern="^[^｡-ﾟ\x00-\x1f\x7f\x20-\x2f\x3a-\x40\x5b-\x60\x7b-\x7e]*$"></td>

</tr>
<?php endforeach; ?>

</table>

</form>
</body>
<script>

(function() {
    new Concerto.ButtonMenu('#com_button');
})();

function act_exec()
{
    if (!validFormCom(null, ['nm_syohin[]'])) {
        alert("使用できない文字を使用しています");
        return;
    }
    
    if (!document.form1.checkValidity()) {
        alert("入力に不正があります");
        return;
    }
    
    splashView();
    document.form1.submit();
}

</script>
</html>
