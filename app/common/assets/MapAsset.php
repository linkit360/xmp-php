<?php

namespace common\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class MapAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $sourcePath = __DIR__ . '/inspinia';

    public $css = [
    ];

    public $js = [
//        'js/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js',
//        'js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js',
        'js/plugins/d3/d3.min.js',
        'js/plugins/topojson/topojson.js',
        'js/plugins/datamaps/datamaps.all.min.js',
        'js/plugins/peity/jquery.peity.min.js',
    ];

    public $depends = [
        JqueryAsset::class,
    ];
}
