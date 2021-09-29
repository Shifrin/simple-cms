<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class FontAwesomeIconsAsset extends AssetBundle
{
    public $sourcePath = '@bower/components-font-awesome';

    public $css = [
        'css/font-awesome.min.css',
    ];
}