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
class MixitupAsset extends AssetBundle
{
    public $sourcePath = '@bower/mixitup';

    public $js = [
        'build/jquery.mixitup.min.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
