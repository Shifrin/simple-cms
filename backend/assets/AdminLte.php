<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AdminLte extends AssetBundle
{
    public $sourcePath = '@bower/admin-lte';

    public $css = [
        'bootstrap/css/bootstrap.min.css',
        'dist/css/AdminLTE.min.css',
        'dist/css/skins/skin-blue.min.css',
    ];

    public $js = [
        'bootstrap/js/bootstrap.min.js',
        'plugins/fastclick/fastclick.min.js',
        'plugins/slimScroll/jquery.slimscroll.min.js',
        'dist/js/app.js',
    ];

//    private $_plugins = [
//        'bootstrap-slider' => [
//            'css' => [
//                'slider.css'
//            ],
//            'js' => [
//                'bootstrap-slider.js'
//            ],
//        ],
//        'bootstrap-wysihtml5' => [
//            'css' => [
//                'bootstrap3-wysihtml5.min.css'
//            ],
//            'js' => [
//                'bootstrap3-wysihtml5.all.min.js'
//            ],
//        ],
//        'chartjs' => [
//            'js' => [
//                'Chart.min.js'
//            ],
//        ],
//        'ckeditor' => [
//            'css' => [
//                'contents.css'
//            ],
//            'js' => [
//                'ckeditor.js',
//                'styles.js',
//            ],
//        ],
//        'ckeditor' => [
//            'css' => [
//                'contents.css'
//            ],
//            'js' => [
//                'ckeditor.js',
//                'styles.js',
//            ],
//        ],
//    ];
}