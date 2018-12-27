<?php
use Concerto\pattern\MementoCookieManager;
use Concerto\standard\Session;
use Concerto\FiscalYear;
use seiban_kanri2\model\CyubanSonekiOriginator;

require_once('../_function/login.php');
require_once('../_function/ComFunc.php');

$globalSession = new Session();
$session = new Session('seiban_kanri');

if (isset($configSystem['cookie']['default'])) {
    try {
        $originator = new CyubanSonekiOriginator();
        $manager = new MementoCookieManager(
            'seiban_kanri',
            $configSystem['cookie']['default'],
            $originator
        );
        $previous = $manager->getStorage();
    } catch (Exception $e) {
        echo 'cookie read error';
    }
}

$kb_nendo = $previous['kb_nendo']?? FiscalYear::getPresentNendo();
$cd_bumon = $previous['cd_bumon']?? $globalSession->input_group;
$chk_nendo_all = $previous['chk_nendo_all']?? '1';
$chk_kansei = $previous['chk_kansei']?? '1';
$chk_job = $previous['chk_job']?? '';
$narrow_tanto = $previous['narrow_tanto']?? null;

$no_cyu = $previous['no_cyu']?? '';
$no_ko = $previous['no_ko']?? '';
$fg_koban = $previous['fg_koban']?? '';
$fg_crt = $previous['fg_crt']?? '';
$fg_lock = $previous['fg_lock']?? '';
$fg_cyuban = $previous['fg_cyuban']?? '';
$fg_project = $previous['fg_project']?? '';
$cd_tmp = $previous['cd_tmp']?? '';
$no_seq = $previous['no_seq']?? '';

$session->kb_nendo = $session->kb_nendo?? $kb_nendo;
$session->cd_bumon = $session->cd_bumon?? $cd_bumon;
$session->chk_nendo_all = $session->chk_nendo_all?? $chk_nendo_all;
$session->chk_kansei = $session->chk_kansei?? $chk_kansei;
$session->chk_job = $session->chk_job?? $chk_job;
$session->narrow_tanto = $session->narrow_tanto?? $narrow_tanto;

$session->cd_kisyu = $session->cd_kisyu?? '';
$session->no_bunya_eigyo = $session->no_bunya_eigyo?? '';
$session->no_bunya_seizo = $session->no_bunya_seizo?? '';

$session->no_cyu = $session->no_cyu?? $no_cyu;
$session->no_ko = $session->no_ko?? $no_ko;
$session->fg_koban = $session->fg_koban?? $fg_koban;
$session->fg_crt = $session->fg_crt?? $fg_crt;
$session->fg_lock = $session->fg_lock?? $fg_lock;
$session->fg_cyuban = $session->fg_cyuban?? $fg_cyuban;
$session->fg_project = $session->fg_project?? $fg_project;
$session->cd_tmp = $session->cd_tmp?? $cd_tmp;
$session->no_seq = $session->no_seq?? $no_seq;
