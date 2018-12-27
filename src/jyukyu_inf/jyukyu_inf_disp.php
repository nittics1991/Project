<?php
use jyukyu_inf\model\JyukyuInfDispControllerModel;
use jyukyu_inf\model\JyukyuInfDispFactory;
use jyukyu_inf\view\JyukyuInfDispView;

require_once('login.php');

$pdo = _getDBConSingleton($configSystem);
$model = new JyukyuInfDispControllerModel(new JyukyuInfDispFactory($pdo));

$model->setQuery();
$model->setAct();

try {
    switch ($model->act) {
        case 'insert':
        case 'update':
        case 'delete':
            if ($model->isValidJyukyuPost()) {
                $model->setJyukyu();
            }
            break;
        case 'ukeire':
            if ($model->isValidUkeirePost()) {
                $model->setUkeire();
            }
            break;
        case 'allukeire':
            if ($model->isValidAllUkeirePost()) {
                $model->setAllUkeire();
            }
            break;
    }
} catch (Exception $e) {
    $log = _getLogSingleton($configSystem);
    $log->write(array(date('Ymd His'), _expansionException($e)));
}

$model->buildData();
$view = new JyukyuInfDispView($model->toArray());
