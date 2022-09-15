<?php

namespace app\assets;
use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'myassets/app.css',
        'myassets/table.css',
    
        
    ];
    public $js = [
        ['myassets/jquery.min.js', 'position' => \yii\web\View::POS_HEAD ],
        'myassets/jquery.dataTables.min.js',
        'myassets/app.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
