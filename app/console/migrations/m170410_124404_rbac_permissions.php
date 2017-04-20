<?php

use yii\db\Migration;

class m170410_124404_rbac_permissions extends Migration
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
        // "Admin" role
        $admin = $this->auth->getRole('Admin');

        // "User" role
        $user = $this->auth->getRole('User');

        // add other permissions
        $toAdd = [
            'blacklistManage' => 'Blacklist Management',
            'campaignsManage' => 'Campaigns Management',
        ];

        foreach ($toAdd as $name => $desc) {
            $perm = $this->auth->createPermission($name);
            $perm->description = $desc;
            $this->auth->add($perm);
            $this->auth->addChild($admin, $perm);
            $this->auth->addChild($user, $perm);
        }
    }

    public function safeDown()
    {
        $toRemove = [
            'blacklistManage' => 'Blacklist Management',
            'campaignsManage' => 'Campaigns Management',
        ];

        foreach ($toRemove as $objName) {
            $perm = $this->auth->getPermission($objName);
            if ($perm) {
                $this->auth->remove($perm);
            }

            $role = $this->auth->getRole($objName);
            if ($role) {
                $this->auth->remove($role);
            }
        }
    }
}
