<?php

namespace frontend\models\Users;

use yii\base\Model;

use common\models\Users;

/**
 * Create User Form
 */
class CreateForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $new_pass;
    public $status;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            [
                'username',
                'unique',
                'targetClass' => '\common\models\Users',
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
                'targetClass' => '\common\models\Users',
                'message' => 'This email address has already been taken.',
            ],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['new_pass', 'safe'],
            ['status', 'safe'],
        ];
    }

    /**
     * Create User
     *
     * @return Users|null the saved model or null if saving fails
     */
    public function create()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new Users();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->new_pass = (integer)$this->new_pass;
        $user->status = (integer)$this->status;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        return $user->save() ? $user : null;
    }
}
