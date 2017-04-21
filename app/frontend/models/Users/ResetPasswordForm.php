<?php

namespace frontend\models\Users;

use Yii;
use yii\base\Model;
use yii\base\InvalidParamException;
use common\models\Users;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;
    /**
     * @var \common\models\Users
     */
    private $_user;

    /**
     * Creates a form model given a token.
     *
     * @param array $config name-value pairs that will be used to initialize the object properties
     *
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($config = [])
    {
        $this->_user = Users::find()
            ->where([
                'id' => Yii::$app->user->id,
                'status' => 1,
            ])
            ->one();

        if (!$this->_user) {
            throw new InvalidParamException('Wrong password reset token.');
        }

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->new_pass = 0;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();
        return $user->save(false);
    }
}
