<?php
use Concerto\conf\Config;
use Concerto\conf\ConfigReaderArray;
use seiban_kanri2\model\CyubanSonekiDispControllerModel;
use seiban_kanri2\model\CyubanSonekiDispFactory;
use seiban_kanri2\view\CyubanSonekiDispView;

require_once('login.php');

$pdo = _getDBConSingleton($configSystem);
$model = new CyubanSonekiDispControllerModel(
    new CyubanSonekiDispFactory($pdo)
);

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

$config = new Config(new ConfigReaderArray('../_config/seiban_kanri2/cyuban_soneki_disp_frame3.php'));
$model->buildData($config);
$view = new CyubanSonekiDispView($model->toArray());
