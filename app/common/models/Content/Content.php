<?php

namespace common\models\Content;

use yii\db\ActiveRecord;

use common\helpers\LogsHelper;

/**
 * @property string  $id
 * @property string  $id_user
 * @property string  $id_category
 * @property string  $id_publisher
 * @property string  $title
 * @property string  $filename
 * @property integer $status
 * @property string  $time_create
 * @property string  $blacklist
 */
class Content extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%content}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'id_user',
                    'id_category',
                    'title',
                    'filename',
                ],
                'required',
            ],
            [
                [
                    'id',
                    'id_user',
                    'id_category',
                    'id_publisher',
                    'blacklist',
                ],
                'string',
            ],
            [
                [
                    'status',
                ],
                'integer',
            ],
            [
                [
                    'title',
                    'filename',
                ],
                'string',
                'max' => 32,
            ],
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
            'id_category' => 'Category',
            'id_publisher' => 'Publisher',
            'title' => 'Title',
            'filename' => 'Filename',
            'status' => 'Status',
            'time_create' => 'Created At',
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
