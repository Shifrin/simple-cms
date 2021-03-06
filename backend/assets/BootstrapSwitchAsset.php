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
class BootstrapSwitchAsset extends AssetBundle
{
    public $sourcePath = '@themes/backend/default/assets/switch/dist';

    public $css = [
        'css/bootstrap-switch.min.css',
    ];

    public $js = [
        'js/bootstrap-switch.min.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'backend\assets\AdminLte',
    ];
}