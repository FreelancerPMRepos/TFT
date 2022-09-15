<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class LoginAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'app-assets/l_css/newvaultGlobal.css',
        'app-assets/l_css/simple.css',
        'app-assets/l_css/grid.css',
        'app-assets/l_css/extensionLoginDialogBase.css',
        'app-assets/l_css/button.css',
        'app-assets/l_css/dialoges.css',
        'app-assets/l_css/dialogsimple.css',

        'app-assets/l_css/notifications.css',
        'app-assets/l_css/fontawesome.min.css',
        'app-assets/l_css/login_simple.css',
    ];
    public $js = [
        [
        'app-assets/js/vendors.min.js','position' => \yii\web\View::POS_HEAD],
        'app-assets/vendors/sweetalert/sweetalert.min.js',
        'app-assets/js/plugins.js',
        'app-assets/vendors/magnific-popup/jquery.magnific-popup.min.js',
        'app-assets/vendors/imagesloaded.pkgd.min.js',
        'app-assets/js/custom/custom-script.js?t=32432',
        'app-assets/js/scripts/form-elements.js',
        'app-assets/js/scripts/app-email.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}