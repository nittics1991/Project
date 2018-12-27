<?php require_once('../../../_template/header_top.php'); ?>
<title>注番分析</title>
<style>
.chartbox{
    width:1280px;
    height:800px;
}

</style>
</head>

<body>

<?php foreach ($images as $filseset) : ?>
    <div class="chartbox">
    <img src="<?= $filseset[0]; ?>">
    </div>
    
    <div class="chartbox">
    <img src="<?= $filseset[1]; ?>">
    </div>
<?php endforeach; ?>

</body>
</html>
