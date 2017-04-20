<?php

namespace common\assets;

use yii\bootstrap\BootstrapAsset;
use yii\bootstrap\BootstrapPluginAsset;
use yii\jui\JuiAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;
use yii\web\YiiAsset;

class InspiniaAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $sourcePath = __DIR__ . '/inspinia';

    public $css = [
        'css/animate.css',
        'css/style.css',
    ];

    public $js = [
        'js/inspinia.js',
        'js/plugins/pace/pace.min.js',
        'js/plugins/metisMenu/jquery.metisMenu.js',
        'js/plugins/slimscroll/jquery.slimscroll.min.js',
    ];

    public $depends = [
        YiiAsset::class,
        JqueryAsset::class,
        JuiAsset::class,
        BootstrapAsset::class,
        BootstrapPluginAsset::class,
        FaAsset::class,
    ];
}
