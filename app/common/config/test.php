<?php
return yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/main.php'),
    [
        'id' => 'app-common-tests',
        'basePath' => dirname(__DIR__),
        'components' => [
            'user' => [
                'class' => 'yii\web\User',
                'identityClass' => \common\models\Users::class,
            ],
        ],
    ]
//    [
//        'components' => [
//            'db' => [
//                'dsn' => 'mysql:host=localhost;dbname=yii2advanced_test',
//            ]
//        ],
//    ]
);



