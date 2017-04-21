<?php

namespace frontend\models\Users;

use function array_merge_recursive;
use const false;
use Yii;
use yii\rbac\ManagerInterface;
use common\models\Logs;
use common\models\Users;
use yii\rbac\Role;

/**
 * @property array            $roles
 * @property array            $rolesAll
 * @property array            $permissionsAll
 * @property ManagerInterface $auth
 */
class UsersForm extends Users
{
    # Fields
    public $roles;
    public $password;

    # Data
    private $rolesAll = [];
    private $auth;

    public function init()
    {
        $this->auth = Yii::$app->authManager;
    }

    public function set()
    {
        $this->roles = array_keys($this->auth->getRolesByUser($this->id));
    }

    public function rules()
    {
        $rules = [
            ['username', 'trim'],
            ['username', 'required'],
            [
                'username',
                'unique',
//                'targetClass' => '\common\models\Users',
                'message' => 'This username has already been taken.',
            ],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            [
                'email',
                'unique',
//                'targetClass' => '\common\models\Users',
                'message' => 'This email address has already been taken.',
            ],


            ['new_pass', 'safe'],
            ['status', 'safe'],
            ['roles', 'safe'],
        ];

        if ($this->isNewRecord) {
            $rules [] = ['password', 'required'];
            $rules [] = ['password', 'string', 'min' => 6];
        }

        return array_merge_recursive(
            parent::rules(),
            $rules
        );
    }

    public function commit()
    {
        if (!$this->validate()) {
            return false;
        }

        if ($this->isNewRecord || $this->password) {
            $this->setPassword($this->password);
        }

        $this->generateAuthKey();

        if (!$this->save()) {
            return false;
        }

        // Remove all roles
        $this->auth->revokeAll($this->id);

        $log = new Logs();
        $log->controller = Yii::$app->requestedAction->controller->id;
        $log->action = Yii::$app->requestedAction->id;
        $ev = [
            'id' => $this->id,
            'roles' => [],
        ];

        // Add roles from form
        if (is_array($this->roles) && count($this->roles)) {
            $ev['roles'] = $this->roles;
            foreach ($this->roles as $role) {
                $role = $this->auth->getRole($role);
                if ($role) {
                    $this->auth->assign($role, $this->id);
                }
            }
        }

        $log->event = $ev;
        $log->save();
        return true;
    }

    public function getRolessAll()
    {
        if (!count($this->rolesAll)) {
            $roles = [];
            /** @var Role $role */
            foreach ($this->auth->getRoles() as $role) {
                $roles[$role->name] = $role->name . ' - ' . $role->description;
            }
            $this->rolesAll = $roles;
        }

        return $this->rolesAll;
    }
}
