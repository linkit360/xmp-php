<?php

$helper = new \common\helpers\ConfigHelper();
# DB Config
$db = $helper->loadConfig('/config/' . YII_ENV . '/db.json');

# AWS S3 Config
$aws = $helper->loadConfig('/config/' . YII_ENV . '/aws.json');
defined('AWS_S3') or define('AWS_S3', $aws);

return [
    'vendorPath' => '/composer/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'pgsql:host=' . $db['host'] . ';port=5432;dbname=' . $db['database'],
            'username' => $db['user'],
            'password' => $db['password'],
            'charset' => 'utf8',
            'tablePrefix' => 'xmp_',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            // the table for storing authorization items. Defaults to "auth_item".
            'itemTable' => '{{%rbac_items}}',
            // the table for storing authorization item hierarchy. Defaults to "auth_item_child".
            'itemChildTable' => '{{%rbac_items_childs}}',
            // the table for storing authorization item assignments. Defaults to "auth_assignment".
            'assignmentTable' => '{{%rbac_assignments}}',
            // the table for storing rules. Defaults to "auth_rule".
            'ruleTable' => '{{%rbac_rules}}',
        ],
    ],
];
