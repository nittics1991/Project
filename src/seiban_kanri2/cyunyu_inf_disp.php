<?php
use seiban_kanri2\model\CyunyuInfDispControllerModel;
use seiban_kanri2\model\CyunyuInfDispFactory;
use seiban_kanri2\view\CyunyuInfDispView;

require_once('login.php');

$pdo = _getDBConSingleton($configSystem);
$model = new CyunyuInfDispControllerModel(new CyunyuInfDispFactory($pdo));

$model->setQuery();

if ($model->isValidPost()) {
    if ($model->keikaku == 'keikaku') {
        try {
            $model->setCyunyuLock();
        } catch (Exception $e) {
            $log = _getLogSingleton($configSystem);
            $log->write(array(date('Ymd His'), _expansionException($e)));
        }
    }
    
    switch ($model->act) {
        case 'insert':
        case 'update':
        case 'delete':
            try {
                $inf = $model->setCyunyuData();
                require_once('../../../_template/header_header.php');
                header("Location:cyunyu_inf_disp.php?no_cyu={$inf['no_cyu']}&no_ko={$inf['no_ko']}");
            } catch (Exception $e) {
                $log = _getLogSingleton($configSystem);
                $log->write(array(date('Ymd His'), _expansionException($e)));
            }
            break;
    }
}

$model->buildData($config);
$view = new CyunyuInfDispView($model->toArray());
