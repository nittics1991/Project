<?php
use seiban_kanri2\model\CyubanSonekiExcelReadControllerModel;
use seiban_kanri2\model\CyubanSonekiExcelReadFactory;
use seiban_kanri2\view\CyubanSonekiExcelReadView;

require_once('login.php');

$pdo = _getDBConSingleton($configSystem);
$model = new CyubanSonekiExcelReadControllerModel(
    new CyubanSonekiExcelReadFactory($pdo)
);

$model->setQuery();

if ($model->isValidPost()) {
    switch ($model->act) {
        case 'upload':
            try {
                $file = $model->readExcel('nm_file');
                @unlink($file);
                echo '終了';
            } catch (Exception $e) {
                $log = _getLogSingleton($configSystem);
                $log->write(array(date('Ymd His'), _expansionException($e)));
                echo 'インポートエラー';
            }
            break;
    }
}

$model->buildData();
$view = new CyubanSonekiExcelReadView($model->toArray());
