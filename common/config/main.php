<?php
return [
    'name' => 'Simple CMS',
    'timeZone' => 'Asia/Colombo',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'aliases' => [
        '@uploads' => dirname(dirname(__DIR__)) . '/uploads'
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'session' => [
            'class' => 'yii\web\Session',
            'name' => '_PHOTOSTORE',
            'savePath' => '@common/runtime/sessions',
//            'cookieParams' => [
//                'path' => '/',
//            ],
        ],
        /**
         * LayoutManager
         */
        'layoutManager' => [
            'class' => 'common\components\LayoutManager',
            'mainLayoutsPath' => '@themes/frontend/main-layouts',
            'partialLayoutsPath' => '@themes/frontend/partial-layouts',
        ],
        /**
         * FileManager
         */
        'fileManager' => [
            'class' => 'common\components\FileManager',
            'thumbnailSizes' => [
                'default' => [400, 250],
                'medium' => [1000, 625],
                'large' => [1600, 1000],
            ]
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager'
        ],
        'formatter' => [
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'QAR',
            'defaultTimeZone' => 'Asia/Qatar'
        ],
    ],
];
