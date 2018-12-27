<?php
use Concerto\standard\HttpDownload;
use seiban_kanri2\model\KobanSonekiExcelControllerModel;
use seiban_kanri2\model\KobanSonekiExcelFactory;

require_once('login.php');


ini_set("max_execution_time", 300);


$pdo = _getDBConSingleton($configSystem);
$model = new KobanSonekiExcelControllerModel(new KobanSonekiExcelFactory($pdo));

$model->setQuery();

$template = __DIR__ . '\\製番情報.xlsx';

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
