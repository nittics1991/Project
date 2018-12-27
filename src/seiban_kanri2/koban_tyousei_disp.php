<?php
use seiban_kanri2\model\KobanTyouseiDispControllerModel;
use seiban_kanri2\model\KobanTyouseiDispFactory;
use seiban_kanri2\view\KobanTyouseiDispView;

require_once('login.php');

$pdo = _getDBConSingleton($configSystem);
$model = new KobanTyouseiDispControllerModel(new KobanTyouseiDispFactory($pdo));

$model->setQuery();

if ($model->isValidPost()) {
    switch ($model->act) {
        case 'update':
            try {
                $model->setTyouseiData();
            } catch (Exception $e) {
                $log = _getLogSingleton($configSystem);
                $log->write(array(date('Ymd His'), _expansionException($e)));
            }
            break;
    }
}

$model->buildData();
$view = new KobanTyouseiDispView($model->toArray());
