<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/params.php')
);

$config = [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'cookieValidationKey' => 'sWOHQyhRudhCz9j_z5s4BW_5p3dtjVCe',
        ],
        'session' => [
            'name' => '_advanced-frontend',
        ],
        'user' => [
            'identityClass' => \common\models\Users::class,
            'enableAutoLogin' => true,
            'loginUrl' => '/main/login',
            'identityCookie' => [
                'name' => '_identity-frontend',
                'httpOnly' => true,
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => yii\log\FileTarget::class,
                    'levels' => [
                        'error',
                        'warning',
                    ],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'main/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                '' => 'main/index',
                'lp/<url:(.*)>' => 'landing-page/template',
                'debug/<controller>/<action>' => 'debug/<controller>/<action>',
                '<controller:\w+(-\w+)*>/<id:\d+>' => '<controller>/view',
                '<controller:\w+(-\w+)*>/<id:\\w+-\\w+-\\w+-\\w+-\\w+>' => '<controller>/view',
                '<controller:\w+(-\w+)*>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+(-\w+)*>/<action:\w+>/<id:\\w+-\\w+-\\w+-\\w+-\\w+>' => '<controller>/<action>',
                '<controller:\w+(-\w+)*>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
    ],
    'modules' => [
        'gridview' => [
            'class' => '\kartik\grid\Module',
        ],
    ],
    'params' => $params,
];

// || YII_ENV === 'testing'
if (defined('YII_ENV') && (YII_ENV === 'development')) {
    $config['components']['assetManager']['forceCopy'] = true;

    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => \yii\debug\Module::class,
        'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => \yii\gii\Module::class,
        'allowedIPs' => ['*'],
    ];
}

return $config;
