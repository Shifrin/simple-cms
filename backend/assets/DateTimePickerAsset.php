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
class DateTimePickerAsset extends AssetBundle
{
    public $sourcePath = '@themes/backend/default/assets/datetimepicker/build';

    public $css = [
        'css/bootstrap-datetimepicker.min.css',
    ];

    public $js = [
        'js/moment.min.js',
        'js/bootstrap-datetimepicker.min.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}