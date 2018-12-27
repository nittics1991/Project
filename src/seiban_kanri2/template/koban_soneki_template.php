<?php require_once('../../../_template/header_top.php'); ?>
<?php require_once('../../../_template/header_jquery.php'); ?>
<?php require_once('../../../_template/header_jquery_ui.php'); ?>
<?php require_once('../../../_template/header_sigmagrid_css_std.php'); ?>
<?php require_once('../../../_template/header_sigmagrid_js.php'); ?>
<?php require_once('../../../_template/header_number_format.php'); ?>
<?php require_once('../../../_template/header_buttonmenu.php'); ?>

<title>製番別詳細情報画面</title>

<style>
.th1{
    width:55px;
}

</style>
</head>

<body>
<?php require_once('../../../_template/splash_window.php'); ?>

<form name="form1" target="_self" method="POST" enctype="multipart/form-data">
<input type="hidden" name="token" value="<?= $this->csrf; ?>">
<input type="hidden" name="act" id="act" value="">
<input type="hidden" name="kb_tanto" id="kb_tanto" value="<?= $kb_tanto; ?>">
<input type="hidden" name="dt_hatuban" id="dt_hatuban" value="<?= $dt_hatuban; ?>">
<input type="hidden" name="dt_kakunin" id="dt_kakunin" value="<?= $dt_kakunin; ?>">
<table class="table-button">
<tr>
<td id="com_button"></td>
<td><a class="ui-state-default" href="cyuban_soneki_disp.php" target="_top" title="製番管理画面に戻ります">戻る</a></td>
<td><a class="ui-state-default" href="koban_soneki_disp.php?no_cyu=<?= $no_cyu_tmp; ?>&fg_koban=<?php if ($fg_koban) {
    echo '0';
                                                                   } else {
    echo '1';
} ?>&fg_project=<?= $fg_project; ?>" target="_top" title="概要／月別表示を切り替えます">
表示切替</a></td>

<?php if (!$fg_project) : ?>
<td><a class="ui-state-default" href="koban_tyousei_disp.php?no_cyu=<?= $no_cyu; ?>" target="_top" title="調整値の登録を行います">調整値設定</a></td>

    <?php if ($kb_tanto) : ?>
<td><a class="ui-state-default" href="#" target="_self" onClick="tanto_exec(this)" title="手動設定した絞り込み条件を取消します">担当解除</a></td>
    <?php else : ?>
<td><a class="ui-state-default" href="#" target="_self" onClick="tanto_exec(this)" title="ログインユーザを製番管理画面の絞り込み条件に設定します">担当設定</a></td>
    <?php endif; ?>

    <?php if (empty($dt_kakunin)) : ?>
<td><a class="ui-state-default" href="#" target="_self" onClick="hatuban_exec(this)" title="発番を確認した事を記憶します">発番確認</a></td>
    <?php else : ?>
<td><a class="ui-state-default" href="#" target="_self" onClick="hatuban_exec(this)" title="発番確認を取り消します">発番確認解除</a></td>
    <?php endif; ?>

<td><a class="ui-state-default" href="#" target="_self" onClick="replace_exec(this)" title="自部門項番の過去月計画値を実績値に置換します">計画値実績置換</a></td>

<td><a class="ui-state-default" href="../wf_new2/wf_seiban_disp.php?no_cyu=<?= $no_cyu; ?>&no_page=0" target="_blank" title="ワークフロー詳細画面を表示します">ワークフロー</a></td>

<td><a class="ui-state-default" href="koban_soneki_chart_make.php?no_cyu=<?= $no_cyu; ?>" target="_blank" title="データをグラフ表示します">分析グラフ</a></td>
<td><a class="ui-state-default" href="koban_soneki_excel_make.php?no_cyu=<?= $no_cyu; ?>"   target="_blank" title="データをEXCELファイルでダウンロードします">Excel出力</a></td>

<?php endif; ?>

<td><a class="ui-state-default" href="/public/メニュー資料2/koban_soneki_disp.pdf" target="_blank" title="操作マニュアルを表示します">？</a></td>

</tr>
</table>
</form>



<table>

<tr>
<th colspan="2">注番</th>
<td class="td-even-left" colspan="10"><?= $no_cyu; ?></td>
<th colspan="2">設置場所</th>
<td class="td-even-left" colspan="10"><?= $nm_setti; ?></td>
</tr>

<tr>
<th colspan="2">品名</th>
<td class="td-even-left" colspan="10"><?= $nm_syohin; ?></td>
<th colspan="2">客先名称</th>
<td class="td-even-left" colspan="10"><?= $nm_user; ?></td>
</tr>

<tr>
<th class="th1">全体</th>
<th class="th1">SP</th>
<th class="th1">ＴＯＶ</th>
<th class="th1">粗利</th>
<th class="th1">注入計</th>
<th class="th1">製番損益</th>
<th class="th1">損益率</th>
<th class="th1">直課時間</th>
<th class="th1">直課金額</th>
<th class="th1">直材費</th>
<th class="th1">経費</th>
<th class="th1">旅費</th>

<th class="th1">課内</th>
<th class="th1">SP</th>
<th class="th1">ＴＯＶ</th>
<th class="th1">粗利</th>
<th class="th1">注入計</th>
<th class="th1">製番損益</th>
<th class="th1">損益率</th>
<th class="th1">直課時間</th>
<th class="th1">直課金額</th>
<th class="th1">直材費</th>
<th class="th1">経費</th>
<th class="th1">旅費</th>
</tr>

<tr>
<th>計画</th>
<td class="td-odd-right"><?= number_format($yn_sp); ?></td>
<td class="td-odd-right"><?= number_format($yn_ptov1); ?></td>
<td class="td-odd-right"><?= number_format($yn_arari); ?></td>
<td class="td-odd-right"><?= number_format($yn_pcyunyu1); ?></td>
<td class="td-odd-right"><?= number_format($yn_psoneki1); ?></td>
<td class="td-odd-right"><?= number_format($ri_psoneki1, 1); ?></td>
<td class="td-odd-right"><?= number_format($tm_pcyokka1, 2); ?></td>
<td class="td-odd-right"><?= number_format($yn_pcyokka1); ?></td>
<td class="td-odd-right"><?= number_format($yn_pcyokuzai1); ?></td>
<td class="td-odd-right"><?= number_format($yn_petc1); ?></td>
<td class="td-odd-right"><?= number_format($yn_pryohi1); ?></td>

<th>計画</tH>
<td class="td-odd-right"><?= number_format($yn_sp); ?></td>
<td class="td-odd-right"><?= number_format($yn_ptov2); ?></td>
<td class="td-odd-right"><?= number_format($yn_arari); ?></td>
<td class="td-odd-right"><?= number_format($yn_pcyunyu2); ?></td>
<td class="td-odd-right"><?= number_format($yn_psoneki2); ?></td>
<td class="td-odd-right"><?= number_format($ri_psoneki2, 1); ?></td>
<td class="td-odd-right"><?= number_format($tm_pcyokka2, 2); ?></td>
<td class="td-odd-right"><?= number_format($yn_pcyokka2); ?></td>
<td class="td-odd-right"><?= number_format($yn_pcyokuzai2); ?></td>
<td class="td-odd-right"><?= number_format($yn_petc2); ?></td>
<td class="td-odd-right"><?= number_format($yn_pryohi2); ?></td>
</tr>

<tr>
<th>予測&nbsp;<a href="/public/メニュー資料2/cyuban_soneki_disp2.pdf" target="_blank" title="予測値の計算式を表示します">?</a></th>
<td class="td-even-right"><?= number_format($yn_sp); ?></td>
<td class="td-even-right"><?= number_format($yn_ytov1); ?></td>
<td class="td-even-right"><?= number_format($yn_arari); ?></td>
<td class="td-even-right"><?= number_format($yn_ycyunyu1); ?></td>
<td class="td-even-right"><?= number_format($yn_ysoneki1); ?></td>
<td class="td-even-right"><?= number_format($ri_ysoneki1, 1); ?></td>
<td class="td-even-right"><?= number_format($tm_ycyokka1, 2); ?></td>
<td class="td-even-right"><?= number_format($yn_ycyokka1); ?></td>
<td class="td-even-right"><?= number_format($yn_ycyokuzai1); ?></td>
<td class="td-even-right"><?= number_format($yn_yetc1); ?></td>
<td class="td-even-right"><?= number_format($yn_yryohi1); ?></td>

<th>予測&nbsp;<a href="/public/メニュー資料2/cyuban_soneki_disp2.pdf" target="_blank" title="予測値の計算式を表示します">?</a></th>
<td class="td-even-right"><?= number_format($yn_sp); ?></td>
<td class="td-even-right"><?= number_format($yn_ytov2); ?></td>
<td class="td-even-right"><?= number_format($yn_arari); ?></td>
<td class="td-even-right"><?= number_format($yn_ycyunyu2); ?></td>
<td class="td-even-right"><?= number_format($yn_ysoneki2); ?></td>
<td class="td-even-right"><?= number_format($ri_ysoneki2, 1); ?></td>
<td class="td-even-right"><?= number_format($tm_ycyokka2, 2); ?></td>
<td class="td-even-right"><?= number_format($yn_ycyokka2); ?></td>
<td class="td-even-right"><?= number_format($yn_ycyokuzai2); ?></td>
<td class="td-even-right"><?= number_format($yn_yetc2); ?></td>
<td class="td-even-right"><?= number_format($yn_yryohi2); ?></td>
</tr>

<tr>
<th>実績</th>
<td class="td-odd-right"><?= number_format($yn_sp); ?></td>
<td class="td-odd-right"><?= number_format($yn_rtov1); ?></td>
<td class="td-odd-right"><?= number_format($yn_arari); ?></td>
<td class="td-odd-right"><?= number_format($yn_rcyunyu1); ?></td>
<td class="td-odd-right"><?= number_format($yn_rsoneki1); ?></td>
<td class="td-odd-right"><?= number_format($ri_rsoneki1, 1); ?></td>
<td class="td-odd-right"><?= number_format($tm_rcyokka1, 2); ?></td>
<td class="td-odd-right"><?= number_format($yn_rcyokka1); ?></td>
<td class="td-odd-right"><?= number_format($yn_rcyokuzai1); ?></td>
<td class="td-odd-right"><?= number_format($yn_retc1); ?></td>
<td class="td-odd-right"><?= number_format($yn_rryohi1); ?></td>

<th>実績</th>
<td class="td-odd-right"><?= number_format($yn_sp); ?></td>
<td class="td-odd-right"><?= number_format($yn_rtov2); ?></td>
<td class="td-odd-right"><?= number_format($yn_arari); ?></td>
<td class="td-odd-right"><?= number_format($yn_rcyunyu2); ?></td>
<td class="td-odd-right"><?= number_format($yn_rsoneki2); ?></td>
<td class="td-odd-right"><?= number_format($ri_rsoneki2, 1); ?></td>
<td class="td-odd-right"><?= number_format($tm_rcyokka2, 2); ?></td>
<td class="td-odd-right"><?= number_format($yn_rcyokka2); ?></td>
<td class="td-odd-right"><?= number_format($yn_rcyokuzai2); ?></td>
<td class="td-odd-right"><?= number_format($yn_retc2); ?></td>
<td class="td-odd-right"><?= number_format($yn_rryohi2); ?></td>
</tr>

</table>

<div id="taskGrid"></div>

</body>

<script>

(function() {
    new Concerto.ButtonMenu('#com_button');
})();

(function() {
    <?php
    if ($fg_koban == '1') {
        require_once('koban_soneki_template12.php');
    } else {
        require_once('koban_soneki_template11.php');
    }
    
    ?>
    <?php require_once('../../../_template/sigmagrid_height.php'); ?>
    <?php require_once('../../../_template/sigmagrid_render.php'); ?>
})();

function tanto_exec(elm)
{
    document.getElementById("act").value = "tanto";
    var flg = document.getElementById("kb_tanto").value;
    
    if (flg == '1') {
        document.getElementById("kb_tanto").value = 0;
    } else {
        document.getElementById("kb_tanto").value = 1;
    }
    
    document.form1.submit();
}

function hatuban_exec(elm)
{
    document.getElementById("act").value = "hatuban";
    document.form1.submit();
}

function replace_exec(elm)
{
    if (!confirm("自部門項番の過去月計画値を実績値に置換します")) {
        return;
    }
    document.getElementById("act").value = "replace";
    splashView();
    document.form1.submit();
}

</script>
</html>
