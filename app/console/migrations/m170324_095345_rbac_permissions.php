<?php

use yii\db\Migration;

class m170324_095345_rbac_permissions extends Migration
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
        $admin = $this->auth->createRole('Admin');
        $admin->description = 'Admin (without usersCreate)';
        $this->auth->add($admin);

        // "AdminCreateUser" role
        $adminU = $this->auth->createRole('AdminCreateUser');
        $adminU->description = 'Admin (with usersCreate)';
        $this->auth->add($adminU);

        // "User" role
        $user = $this->auth->createRole('User');
        $user->description = 'User';
        $this->auth->add($user);

        // add old permissions to "admin" role
        $perm = $this->auth->getPermission('reportsAdvertisingView');
        $this->auth->addChild($admin, $perm);

        $perm = $this->auth->getPermission('reportsConversionView');
        $this->auth->addChild($admin, $perm);

        $perm = $this->auth->getPermission('reportsRevenueView');
        $this->auth->addChild($admin, $perm);

        $perm = $this->auth->getPermission('logsView');
        $this->auth->addChild($admin, $perm);

        // add "usersCreate" permission
        $perm = $this->auth->createPermission('usersCreate');
        $perm->description = 'Create Users';
        $this->auth->add($perm);
        $this->auth->addChild($adminU, $perm);

        // add other permissions
        $toAdd = [
            'usersManage' => 'Users Management',
            'rbacManage' => 'RBAC Management',

//            'blacklistManage' => 'Blacklist Management',
            'operatorsManage' => 'Operators Management',
            'providersManage' => 'Providers Management',
            'countriesManage' => 'Countries Management',

            'monitoringView' => 'Monitoring View',
            'lpCreate' => 'Landing Page Creation',
        ];

        foreach ($toAdd as $name => $desc) {
            $perm = $this->auth->createPermission($name);
            $perm->description = $desc;
            $this->auth->add($perm);
            $this->auth->addChild($admin, $perm);
        }

        // "lpCreate" to "User"
        $perm = $this->auth->getPermission('lpCreate');
        $this->auth->addChild($user, $perm);

        // superadmin to "test" user
        $this->auth->assign($adminU, '96c571ea-2a95-46d3-b2ad-f8b3d1c9ee6a');
        $this->auth->assign($admin, '96c571ea-2a95-46d3-b2ad-f8b3d1c9ee6a');
    }

    public function safeDown()
    {
        $toRemove = [
            // Permissions
            'usersCreate',
            'usersManage',
            'rbacManage',

//            'blacklistManage',
            'operatorsManage',
            'providersManage',
            'countriesManage',

            'monitoringView',
            'lpCreate',

            // Roles
            'Admin',
            'AdminCreateUser',
            'User',
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
