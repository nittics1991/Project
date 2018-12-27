<?php require_once('../../../_template/header_top.php'); ?>
<?php require_once('../../../_template/header_valid_form.php'); ?>

<title>EXCEL入力画面</title>
<style>
.table1 {
    width:580px;
}

.th1{
    width:80px;
}

.file1{
    width:500px;
}

#message{
    width:600px;
    margin:4px;
    font-size:14px;
    color:#ff0000;
}
</style>

</head>

<body>
<form name="form1" target="_self" method="POST" enctype="multipart/form-data">
<input type="hidden" name="token" value="<?= $this->csrf; ?>">
<input type="hidden" name="MAX_FILE_SIZE" value="1000000">
<input type="hidden" name="act" value="upload">
<input type="hidden" name="kb_nendo" value="<?= $kb_nendo; ?>">
<input type="hidden" name="cd_bumon" value="<?= $cd_bumon; ?>">

<table class="table-button table1">
<tr>
<td>
<a class="ui-state-default" href="#" target="_self" onClick="act_exist()" title="指定EXCELから読み込みます">実行</a>
<a class="ui-state-default" href="cyuban_soneki_disp.php" target="_top" title="製番管理画面に戻ります">戻る</a>
<a class="ui-state-default" href="/public/メニュー資料2/cyuban_soneki_excel_read.pdf" target="_blank" title="操作マニュアルを表示します">？</a>
</td>
</tr>
</table>

<table>
<tr>
<th class="th1">ファイル</th>
<td class="td1"><input type="file" name="nm_file" class="file1" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required></td>
</tr>
</table>

<br>
<div id="info">
<p>フォーマットはEXCEL出力の「計画」シートです(セルコメントも確認してください)</p>
<p>アクティブシートからインポートします</p>
<p>最大1000行まで、先頭行はタイトル行とし、不要行/列・空行を詰めてください</p>
<p>注番・項番・年月をキーに計画値を削除し、EXCELデータをインポートします</p>
</div>

<div id="message"></div>
<div id="history">
<?php if (!empty($history)) : ?>
    <?php foreach ($history as $key => $val) : ?>
エラーLine/ID:<?= $key; ?><br>
    <?php endforeach; ?>
<?php endif; ?>
</div>
</form>
</body>

<script>
function act_exist()
{
    if (!validFormCom()) {
        alert("使用できない文字を使用しています");
        return;
    }
    
    if (!(document.querySelector("[name=form1]")[0].checkValidity())) {
        alert("入力に不正があります");
        return;
    }
    
    upfile = document.getElementsByName('nm_file');
    
    if (upfile[0].value.length == 0) {
        alert("ファイルを選択してください");
        return;
    }
    
    limit = parseInt(document.getElementsByName("MAX_FILE_SIZE")[0].value);
    
    if (upfile[0].files[0].size > limit) {
        alert("ファイルサイズが大きすぎます");
        return;
    }
    
    if (upfile[0].files[0].type != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
        alert("ファイルサイズタイプが違います");
        return;
    }
    
    document.getElementById("message").innerHTML = "処理中";
    document.form1.submit();
}
</script>

</html>
