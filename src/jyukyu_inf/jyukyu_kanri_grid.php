<?php
use Concerto\standard\StringUtil;
use jyukyu_inf\model\JyukyuKanriDispControllerModel;
use jyukyu_inf\model\JyukyuKanriDispFactory;

require_once('login.php');

$pdo = _getDBConSingleton($configSystem);
$model = new JyukyuKanriDispControllerModel(new JyukyuKanriDispFactory($pdo));

$dataset = $model->buildGridData();

require_once('../../../_template/header_header.php');
print(StringUtil::jsonEncode($dataset));
