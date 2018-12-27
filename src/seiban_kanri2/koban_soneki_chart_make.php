<?php
use seiban_kanri2\model\KobanSonekiChartControllerModel;
use seiban_kanri2\model\KobanSonekiChartFactory;
use seiban_kanri2\view\KobanSonekiChartView;

require_once('login.php');


ini_set("max_execution_time", 300);

$pdo = _getDBConSingleton($configSystem);
$model = new KobanSonekiChartControllerModel(new KobanSonekiChartFactory($pdo));

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

$files = (empty($files))?   array():$files;
$view = new KobanSonekiChartView($files);
