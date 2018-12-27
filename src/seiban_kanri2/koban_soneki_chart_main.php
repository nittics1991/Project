<?php
use seiban_kanri2\model\KobanSonekiChartControllerModel;
use seiban_kanri2\model\KobanSonekiChartFactory;
use seiban_kanri2\model\KobanSonekiChartView;

require_once('../_function/ComFunc.php');


ini_set("max_execution_time", 300);


$pdo = _getDBConSingleton($configSystem);
$model = new KobanSonekiChartControllerModel(new KobanSonekiChartFactory($pdo));

try {
    $model->setQueryChartChildren();
    $files = $model->buildChartChildren();
} catch (Exception $e) {
    $log = _getLogSingleton($configSystem);
    $log->write(array(date('Ymd His'), _expansionException($e)));
}
