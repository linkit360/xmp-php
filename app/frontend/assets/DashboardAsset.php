<?php

namespace frontend\assets;

use yii\web\AssetBundle;

use common\assets\MapAsset;

/**
 * Frontend dashboard asset bundle.
 */
class DashboardAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $sourcePath = __DIR__;

    public $css = [

    ];

    public $js = [
        'dashboard/dashboard.js',
        'dashboard/countries.js',
    ];

    public $depends = [
        MapAsset::class,
    ];
}
