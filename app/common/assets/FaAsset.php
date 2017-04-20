<?php

namespace common\assets;

use yii\web\AssetBundle;

class FaAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $sourcePath = '@vendor/fortawesome/font-awesome';

    public $css = [
        'css/font-awesome.css',
    ];

    public $js = [

    ];

    public $depends = [

    ];
}
