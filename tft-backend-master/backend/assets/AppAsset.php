<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

        // 'https://fonts.googleapis.com/icon?family=Material+Icons',
        'css/site.css',
        'app-assets/vendors/sweetalert/sweetalert.css',
        'app-assets/vendors/vendors.min.css',
        'app-assets/css/themes/vertical-modern-menu-template/materialize.css',
        'app-assets/css/themes/vertical-modern-menu-template/style.css',
        'app-assets/css/pages/login.css',
        'app-assets/css/custom/custom.css',
        'app-assets/css/pages/user-profile-page.css',
        'app-assets/vendors/animate-css/animate.css',
        'app-assets/vendors/chartist-js/chartist.min.css',
        'app-assets/vendors/magnific-popup/magnific-popup.css',
        'app-assets/css/pages/app-sidebar.css',
        'app-assets/css/pages/app-email.css',
        'app-assets/css/custom/drop.css',
        'app-assets/css/pages/app-chat.css',
    ];
    public $js = [
        ['app-assets/js/vendors.min.js','position' => \yii\web\View::POS_HEAD],
      //  ['https://www.jqueryscript.net/demo/Beautiful-JSON-Viewer-Editor/dist/jquery.json-editor.min.js','position' => \yii\web\View::POS_HEAD],
        ['app-assets/js/drop.js'],
        'https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js',
        'app-assets/vendors/sweetalert/sweetalert.min.js',
        'app-assets/js/plugins.js',
        'app-assets/vendors/magnific-popup/jquery.magnific-popup.min.js',
        'app-assets/vendors/imagesloaded.pkgd.min.js',
        'app-assets/js/custom/custom-script.js?t=32432',
        'app-assets/js/scripts/form-elements.js',
        'app-assets/js/scripts/app-email.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}