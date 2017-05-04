<?php

namespace common\models\Content;

use yii\db\ActiveRecord;

use common\helpers\LogsHelper;

/**
 * @property string  $id
 * @property string  $id_user
 * @property string  $title
 * @property string  $description
 * @property integer $status
 */
class Publishers extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%content_publishers}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_user', 'title', 'status'], 'required'],
            [['status'], 'integer'],
            [['id', 'id_user'], 'string'],
            [['title'], 'string', 'max' => 32],
            [['description'], 'string', 'max' => 255],
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
            'title' => 'Title',
            'description' => 'Description',
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
