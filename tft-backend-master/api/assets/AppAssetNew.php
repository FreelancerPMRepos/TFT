<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */




class AppAssetNew extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "assets2/css/animate.css",
        "assets2/css/bootstrap.min.css",
        "assets2/css/fonts.css",
        "assets2/css/flaticon.css",
        "assets2/css/font-awesome.css",
        "assets2/css/owl.carousel.css",
        "assets2/css/owl.theme.default.css",
        "assets2/css/nice-select.css",
        "assets2/css/swiper.css",
        "assets2/css/magnific-popup.css",
        "assets2/css/style.css",
        "assets2/css/dark_theme.css",
        "assets2/css/responsive.css",
    ];    
    public $js = [
        ['ass/assets/vendors/jquery/jquery.min.js', 'position' => \yii\web\View::POS_HEAD ],
        "assets2/js/bootstrap.min.js",
        "assets2/js/modernizr.js",
        "assets2/js/plugin.js",
        "assets2/js/jquery.nice-select.min.js",
        "assets2/js/jquery.inview.min.js",
        "assets2/js/jquery.magnific-popup.js",
        "assets2/js/swiper.min.js",
        "assets2/js/owl.carousel.js",
        "assets2/js/custom.js",      
        'ass/assets/js/groovy.js'
    ];


    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
