<?php
use Concerto\pattern\MementoCookieManager;
use Concerto\standard\Session;
use Concerto\FiscalYear;
use jyukyu_inf\model\JyukyuKanriOriginator;

require_once('../_function/login.php');
require_once('../_function/ComFunc.php');

$globalSession = new Session();
$session = new Session('jyukyu_inf');

if (isset($configSystem['cookie']['default'])) {
    try {
        $originator = new JyukyuKanriOriginator();
        $manager = new MementoCookieManager(
            'jyukyu_inf',
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

$session->kb_nendo  = $session->kb_nendo?? $kb_nendo;
$session->cd_bumon  = $session->cd_bumon?? $cd_bumon;
$session->chk_nendo_all = $session->chk_nendo_all?? $chk_nendo_all;
$session->chk_kansei  = $session->chk_kansei?? $chk_kansei;
$session->no_cyu = $session->no_cyu?? '';
