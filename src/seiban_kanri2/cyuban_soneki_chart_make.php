<?php
use seiban_kanri2\model\CyubanSonekiChartControllerModel;
use seiban_kanri2\model\CyubanSonekiChartFactory;
use seiban_kanri2\view\CyubanSonekiChartView;

require_once('login.php');

$pdo = _getDBConSingleton($configSystem);
$model = new CyubanSonekiChartControllerModel(new CyubanSonekiChartFactory($pdo));

$model->setQuery();

try {
    $files = $model->buildChart();
    
    if (!$model->task_result) {
        $log = _getLogSingleton($configSystem);
        $log->write(array(date('Ymd His'), var_export($model->task_error, true)));
    }
} catch (Exception $e) {
    $log = _getLogSingleton($configSystem);
    $log->write(array(date('Ymd His'), _expansionException($e)));
}

$view = new CyubanSonekiChartView((array)$files);
