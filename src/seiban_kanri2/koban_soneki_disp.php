<?php
use seiban_kanri2\model\KobanSonekiDispControllerModel;
use seiban_kanri2\model\KobanSonekiDispFactory;
use seiban_kanri2\view\KobanSonekiDispView;

require_once('login.php');

$pdo = _getDBConSingleton($configSystem);
$model = new KobanSonekiDispControllerModel(new KobanSonekiDispFactory($pdo));

$model->setQuery();

if ($model->isValidPost()) {
    try {
        switch ($model->act) {
            case 'tanto':
                $model->setSeibanTanto($input_code, $no_cyu, (bool)$kb_tanto);
                break;
            case 'hatuban':
                $model->setHatubanKakunin();
                break;
            case 'replace':
                $model->replacePerformanceToPlan();
                break;
        }
    } catch (Exception $e) {
        $log = _getLogSingleton($configSystem);
        $log->write(array(date('Ymd His'), _expansionException($e)));
    }
}

$model->buildData();
$view = new KobanSonekiDispView($model->toArray());
