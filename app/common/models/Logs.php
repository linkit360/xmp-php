<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string  $time
 * @property string  $id_user
 * @property string  $controller
 * @property string  $action
 * @property string  $event
 */
class Logs extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%logs}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'string'],
            [['time'], 'safe'],
            [['id_user', 'controller', 'action'], 'required'],
            [['id_user'], 'string'],
            [['controller', 'action'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Log ID',
            'time' => 'Time',
            'id_user' => 'User ID',
            'controller' => 'Controller',
            'action' => 'Action',
            'event' => 'Event',
        ];
    }

    public function beforeValidate()
    {
        $this->id_user = Yii::$app->user->id;
        $this->event = json_encode($this->event);

        return parent::beforeValidate();
    }

    public function getUser()
    {
        return $this->hasOne(
            Users::className(),
            [
                'id' => 'id_user',
            ]
        );
    }
}
