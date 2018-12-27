<?php
use Concerto\standard\StringUtil;
use seiban_kanri2\model\KobanSonekiDispControllerModel;
use seiban_kanri2\model\KobanSonekiDispFactory;

require_once('login.php');

$pdo = _getDBConSingleton($configSystem);
$model = new KobanSonekiDispControllerModel(new KobanSonekiDispFactory($pdo));

$dataset = $model->buildGridProjectData();

require_once('../../../_template/header_header.php');
print(StringUtil::jsonEncode($dataset));
