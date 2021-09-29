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
class TrumbowygEditorAsset extends AssetBundle
{
    public $sourcePath = '@bower/trumbowyg/dist';

    public $css = [
        'ui/trumbowyg.min.css',
    ];

    public $js = [
        'trumbowyg.min.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}