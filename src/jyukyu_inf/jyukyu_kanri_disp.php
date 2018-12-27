<?php
use jyukyu_inf\model\JyukyuKanriDispControllerModel;
use jyukyu_inf\model\JyukyuKanriDispFactory;
use jyukyu_inf\view\JyukyuKanriDispView;

require_once('login.php');

$pdo = _getDBConSingleton($configSystem);
$model = new JyukyuKanriDispControllerModel(new JyukyuKanriDispFactory($pdo));

$model->setQuery();

if ($model->isValidPost()) {
    switch ($model->act) {
        case 'setEnv':
            $model->setEnv($configSystem['cookie']['default']);
            break;
        case 'resetEnv':
            $model->resetEnv($configSystem['cookie']['default']);
            break;
    }
}

$model->buildData();
$view = new JyukyuKanriDispView($model->toArray());
