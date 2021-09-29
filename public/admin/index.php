<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/yii.php');
require(__DIR__ . '/../../one/One.php');
require(__DIR__ . '/../../common/config/bootstrap.php');
require(__DIR__ . '/../../backend/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/main.php'),
    require(__DIR__ . '/../../common/config/main-local.php'),
    require(__DIR__ . '/../../backend/config/main.php'),
    require(__DIR__ . '/../../backend/config/main-local.php')
);

$application = new \backend\app\Application($config);
$application->run();