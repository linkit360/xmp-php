<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\helpers\LogsHelper;

/**
 * @property string  $id
 * @property string  $id_user
 * @property string  $id_service
 * @property integer $id_operator
 * @property string  $title
 * @property string  $description
 * @property string  $link
 * @property integer $status
 * @property string  $created_at
 * @property string  $updated_at
 */
class Campaigns extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%campaigns}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_user', 'id_service', 'id_operator', 'id_lp', 'title', 'link'], 'required'],
            [['id', 'id_user', 'id_lp', 'id_service'], 'string'],
            [['id_operator', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 128],
            [['description'], 'string', 'max' => 255],
            [['link'], 'string', 'max' => 64],
            [['link'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user' => 'User',
            'id_service' => 'Service',
            'id_operator' => 'Operator',
            'title' => 'Title',
            'description' => 'Description',
            'link' => 'Link',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeValidate()
    {
        $this->id_user = Yii::$app->user->id;
        return parent::beforeValidate();
    }

    public function afterSave($insert, $oldAttributes)
    {
        $logs = new LogsHelper();
        $logs->log(
            $this,
            $oldAttributes
        );

        return parent::afterSave(
            $insert,
            $oldAttributes
        );
    }
}
