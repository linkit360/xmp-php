<?php

namespace frontend\models;

use common\models\Logs;
use function str_replace;
use function trim;
use Yii;
use yii\base\Model;
use yii\rbac\Permission;

/**
 * Rbac Role Form
 */
class RbacForm extends Model
{
    # Fields
    public $name;
    public $description;
    public $isNewRecord = true;
    public $permissions = [];

    # Data
    /** @var \yii\rbac\ManagerInterface */
    public $auth;
    private $permissionsAll = [];
    private $oldName = false;

    public function init()
    {
        $this->auth = Yii::$app->authManager;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'name',
                    'description',
                ],
                'string',
            ],
            [
                [
                    'name',
                    'description',
                    'permissions',
                ],
                'required',
            ],
        ];
    }

    public function set($id)
    {
        $role = $this->auth->getRole($id);

        $this->isNewRecord = false;
        $this->name = $this->oldName = $role->name;
        $this->description = $role->description;
        $this->permissions = array_keys($this->auth->getPermissionsByRole($role->name));
    }

    public function save()
    {
        $this->name = trim($this->name);
        $this->name = ucwords($this->name);
        $this->name = str_replace(' ', '', $this->name);

        if (!$this->validate()) {
            return false;
        }

        $role = $this->auth->getRole($this->oldName);
        if ($this->isNewRecord) {
            if ($role !== null) {
                $this->addError('name', 'Role already exists.');
                return false;
            }
            $role = $this->auth->createRole($this->name);
            $role->name = $this->name;
            $role->description = $this->description;
            $this->auth->add($role);
        } else {
            $role->name = $this->name;
            $role->description = $this->description;
            $this->auth->update($this->oldName, $role);
        }

        $log = new Logs();
        $log->controller = Yii::$app->requestedAction->controller->id;
        $log->action = Yii::$app->requestedAction->id;
        $ev = [
            'id' => $role->name,
            'oldname' => $this->oldName,
        ];

        // Remove all
        foreach ($this->auth->getPermissionsByRole($role->name) as $perm) {
            $this->auth->removeChild($role, $perm);
        }

        // Add from form
        if (count($this->permissions)) {
            foreach ($this->permissions as $perm) {
                $perm = $this->auth->getPermission($perm);
                if ($perm) {
                    $this->auth->addChild($role, $perm);
                    $ev['roles'][] = $perm->name;
                }
            }
        }

        $log->event = $ev;
        $log->save();

        return true;
    }

    public function getPermissionsAll()
    {
        if (!count($this->permissionsAll)) {
            $perms = [];
            /** @var Permission $perm */
            foreach ($this->auth->getPermissions() as $perm) {
                $perms[$perm->name] = $perm->name . ' - ' . $perm->description;
            }
            $this->permissionsAll = $perms;
        }

        return $this->permissionsAll;
    }
}
