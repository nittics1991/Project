<?php
use Concerto\standard\StringUtil;
use jyukyu_inf\model\JyukyuInfDispControllerModel;
use jyukyu_inf\model\JyukyuInfDispFactory;

require_once('login.php');

$pdo = _getDBConSingleton($configSystem);
$model = new JyukyuInfDispControllerModel(new JyukyuInfDispFactory($pdo));

$dataset = $model->buildGridData();

require_once('../../../_template/header_header.php');
print(StringUtil::jsonEncode($dataset));
