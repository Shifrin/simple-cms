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
class AceEditorAsset extends AssetBundle
{
    public $sourcePath = '@bower/ace-build/src-noconflict';

    public $js = [
        'ace.js',
//        'theme-github.js',
//        'mode-php.js',
        'ext-language_tools.js',
    ];

    public $depends = [
        'backend\assets\AdminLte',
    ];
}