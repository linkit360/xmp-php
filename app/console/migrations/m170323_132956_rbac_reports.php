<?php

use yii\db\Migration;

class m170323_132956_rbac_reports extends Migration
{
    /** @var \yii\rbac\ManagerInterface */
    public $auth;

    public function init()
    {
        parent::init();
        $this->auth = Yii::$app->authManager;
    }

    public function safeUp()
    {
        // add "reports" permissions
        $perm = $this->auth->createPermission('reportsAdvertisingView');
        $perm->description = 'Reports Advertising View';
        $this->auth->add($perm);

        $perm = $this->auth->createPermission('reportsConversionView');
        $perm->description = 'Reports Conversion View';
        $this->auth->add($perm);

        $perm = $this->auth->createPermission('reportsRevenueView');
        $perm->description = 'Reports Revenue View';
        $this->auth->add($perm);
    }

    public function safeDown()
    {
        $perm = $this->auth->getPermission('reportsAdvertisingView');
        $this->auth->remove($perm);

        $perm = $this->auth->getPermission('reportsConversionView');
        $this->auth->remove($perm);

        $perm = $this->auth->getPermission('reportsRevenueView');
        $this->auth->remove($perm);
    }
}
