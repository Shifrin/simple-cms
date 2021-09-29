<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'backend',
    'name' => 'Simple CMS Administration',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'defaultRoute' => 'dashboard/index',
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
//            'accessChecker' => new \common\components\AccessChecker(),
            'enableAutoLogin' => true,
            'loginUrl' => ['/user/login'],
//            'identityCookie' => [
//                'name' => '_backendUser',
//                'path' => '/admin'
//            ]
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => '/home/error',
        ],
        'urlManager' => [
            'baseUrl' => '/admin',
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [
                '/' => 'home/index',
                '<controller:\w+>' => '<controller>/index',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
        /**
         * UrlManager
         */
        'frontUrlManager' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => '/',
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => 'home/index',
                [
                    'pattern' => 'user/reset-password/<token:[\w\-]+>',
                    'route' => 'user/reset-password',
                    'encodeParams' => false,
                ],
                [
                    'pattern' => 'user/verify-email/<token:[\w\-]+>',
                    'route' => 'user/verify-email',
                    'encodeParams' => false,
                ],
                'blog/<slug>' => 'blog/index',
                'blog' => 'blog/index',
                '<slug:[-a-zA-Z]+>' => 'home/page',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:[\w\-]+>' => '<controller>/<action>',
            ],
        ],
        'view' => [
            'theme' => [
                'basePath' => '@themes/backend/default',
                'baseUrl' => '@themes/backend/default',
                'pathMap' => [
                    '@app/views' => '@themes/backend/default'
                ]
            ]
        ],
//        'session' => [
//            'class' => 'yii\web\Session',
//            'name' => '_backend',
//            'savePath' => __DIR__ . '/../runtime/sessions',
//            'cookieParams' => [
//                'path' => '/admin',
//            ],
//        ],
        'assetManager' => [
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => false,
                'yii\bootstrap\BootstrapPluginAsset' => false
            ],
            //'forceCopy' => true // Uncomment on production
        ],
        'request' => [
            'cookieValidationKey' => 'umrcVXTveThM9gUGiXSX',
            'csrfParam' => '_backendCSRF',
        ],
    ],
    'params' => $params,
];
