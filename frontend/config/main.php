<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'defaultRoute' => 'home/index',
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['user/login'],
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
            'errorAction' => 'home/error',
        ],
        'urlManager' => [
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
        /**
         * UrlManager
         */
        'backUrlManager' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => '/admin',
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [
                '/' => 'home/index',
                '<controller:[\w\-]+>' => '<controller>/index',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
        'view' => [
            'theme' => [
                'basePath' => '@themes/frontend/maxima',
                'baseUrl' => '@themes/frontend/maxima',
                'pathMap' => [
                    '@app/views' => '@themes/frontend/maxima'
                ]
            ]
        ],
        'assetManager' => [
            'forceCopy' => true // Uncomment on production
        ],
        'request' => [
            'cookieValidationKey' => 'P2jVXwkPBtKnijSgCQrl',
            'csrfParam' => '_frontendCSRF',
        ],
    ],
    'params' => $params,
];
