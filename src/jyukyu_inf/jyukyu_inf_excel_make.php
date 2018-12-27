<?php
use Concerto\standard\HttpDownload;
use jyukyu_inf\model\JyukyuInfExcelControllerModel;
use jyukyu_inf\model\JyukyuInfExcelFactory;

require_once('login.php');

$pdo = _getDBConSingleton($configSystem);
$model = new JyukyuInfExcelControllerModel(new JyukyuInfExcelFactory($pdo));

$template = __DIR__ . '\\受給品情報.xlsx';

try {
    $file = $model->buildExcel($template);
    
    require_once('../../../_template/header_header.php');
    $downloader = new HttpDownload();
    $downloader->send($file);
    
    unlink(mb_convert_encoding($file, 'SJIS', 'UTF8'));
} catch (Exception $e) {
    $log = _getLogSingleton($configSystem);
    $log->write(array(date('Ymd His'), _expansionException($e)));
}
