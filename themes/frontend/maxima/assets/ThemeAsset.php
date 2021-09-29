<?php

namespace themes\frontend\maxima\assets;

use yii\web\AssetBundle;

/**
 * ThemeAsset Class File
 *
 * Add description here
 *
 * @author Mohammad Shifreen
 * @project Arab Photo Store
 * @copyright 2016 Mohammed Shifreen
 */
class ThemeAsset extends AssetBundle
{
    public $sourcePath = '@themes/frontend/maxima/assets';

    public $css = [
        'js/owl-carousel/owl.carousel.css',
        'js/owl-carousel/owl.theme.css',
        'js/owl-carousel/owl.transitions.css',
        'js/rs-plugin/css/settings.css',
        'js/flexslider/flexslider.css',
        'js/isotope/isotope.css',
        'css/jquery-ui.css',
        'js/magnific-popup/magnific-popup.css',
        'css/style.css',
        'css/color-scheme/default-black.css',
    ];

    public $js = [
        'js/menu.js',
        'js/owl-carousel/owl.carousel.min.js',
        'js/rs-plugin/js/jquery.themepunch.tools.min.js',
        'js/rs-plugin/js/jquery.themepunch.revolution.min.js',
        'js/jquery.easing.min.js',
        'js/isotope/isotope.pkgd.js',
        'js/jflickrfeed.min.js',
        'js/tweecool.js',
        'js/flexslider/jquery.flexslider.js',
        'js/easypie/jquery.easypiechart.min.js',
        'js/jquery-ui.js',
        'js/jquery.appear.js',
        'js/jquery.inview.js',
        'js/jquery.countdown.min.js',
        'js/jquery.sticky.js',
        'js/magnific-popup/jquery.magnific-popup.min.js',
        'js/main.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'themes\frontend\maxima\assets\GoogleFontsAsset',
        'common\assets\FontAwesomeIconsAsset',
        'common\assets\IonIconsAsset',
    ];
}