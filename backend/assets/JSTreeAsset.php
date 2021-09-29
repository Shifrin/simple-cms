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
class JSTreeAsset extends AssetBundle
{
    public $sourcePath = '@themes/backend/default/assets/jstree';

    public $css = [
        'dist/themes/default/style.min.css'
    ];

    public $js = [
        'dist/jstree.min.js'
    ];

    public $publishOptions = [
        'only' => [
            'dist/*',
        ]
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}