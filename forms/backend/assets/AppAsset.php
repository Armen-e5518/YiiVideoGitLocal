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
        'css/layoutit.css',
        'css/src.css',
        'css/main.css',
        'css/site.css',
        'css/admin/awesome/css/font-awesome.css',
        'css/admin/awesome/css/bootstrap.css',
        'css/admin/AdminLTE.min.css',
        'css/admin/_all-skins.min.css',
    ];
    public $js = [
//        'js/jquery/jquery.js',
        'js/admin/app.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}
