<?php
use Concerto\standard\HttpDownload;
use seiban_kanri2\model\CyubanSonekiExcelControllerModel;
use seiban_kanri2\model\CyubanSonekiExcelFactory;

require_once('login.php');

$pdo = _getDBConSingleton($configSystem);
$model = new CyubanSonekiExcelControllerModel(
    new CyubanSonekiExcelFactory($pdo)
);

$model->setQuery();

$template = __DIR__ . '\\製番管理表.xlsx';

try {
    $file = $model->buildExcel($template);
    
    require_once('../../../_template/header_header.php');
    $downloader = new HttpDownload();
    $downloader->send($file);
    
    unlink(mb_convert_encoding($file, 'SJIS', 'UTF-8'));
} catch (Exception $e) {
    $log = _getLogSingleton($configSystem);
    $log->write(array(date('Ymd His'), _expansionException($e)));
}
