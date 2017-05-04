<?php

namespace common\models;

use common\helpers\LogsHelper;
use Yii;
use yii\db\ActiveRecord;

/**
 * @property string  $id
 * @property string  $id_user
 * @property string  $title
 * @property string  $description
 * @property integer $status
 * @property string  $created_at
 * @property string  $updated_at
 */
class Lps extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%lps}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_user'], 'required'],
            [['id', 'id_user'], 'string'],
            [['title'], 'string', 'max' => 64],
            [['description'], 'string', 'max' => 255],
            [['status'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user' => 'Created By',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
