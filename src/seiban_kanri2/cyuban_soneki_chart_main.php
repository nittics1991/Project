<?php
use seiban_kanri2\model\CyubanSonekiChartControllerModel;
use seiban_kanri2\model\CyubanSonekiChartFactory;
use seiban_kanri2\model\CyubanSonekiChartView;

require_once('../_function/ComFunc.php');

$pdo = _getDBConSingleton($configSystem);
$model = new CyubanSonekiChartControllerModel(new CyubanSonekiChartFactory($pdo));

try {
    $model->setQueryChartChildren();
    $files = $model->buildChartChildren();
} catch (Exception $e) {
    $log = _getLogSingleton($configSystem);
    $log->write(array(date('Ymd His'), _expansionException($e)));
}
