<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Class SpeakingUrlAsset
 * @package backend\assets
 */
class SpeakingUrlAsset extends AssetBundle
{
    public $sourcePath = '@bower/speakingurl';

    public $js = [
        'speakingurl.min.js',
    ];
}