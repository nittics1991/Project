<?php
use Concerto\standard\StringUtil;
use seiban_kanri2\model\CyubanSonekiDispControllerModel;
use seiban_kanri2\model\CyubanSonekiDispFactory;

require_once('login.php');

$pdo = _getDBConSingleton($configSystem);
$model = new CyubanSonekiDispControllerModel(new CyubanSonekiDispFactory($pdo));

$dataset = $model->buildGridData();

require_once('../../../_template/header_header.php');
print(StringUtil::jsonEncode($dataset));
