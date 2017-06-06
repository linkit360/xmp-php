<?php

namespace common\models;

use yii\db\ActiveRecord;
use common\helpers\LogsHelper;

/**
 * @property string  $id
 * @property string  $id_user
 * @property string  $id_service
 * @property string  $id_lp
 * @property string  $title
 * @property string  $description
 * @property string  $link
 * @property integer $status
 * @property string  $created_at
 * @property string  $updated_at
 * @property string  $autoclick_enabled
 * @property string  $autoclick_ratio
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
            [
                [
                    'id_user',
                    'id_service',
                    'id_lp',
                    'title',
                    'link',
                    'autoclick_enabled',
                    'autoclick_ratio',
                ],
                'required',
            ],
            [
                [
                    'id',
                    'id_user',
                    'id_lp',
                    'id_service',
                ],
                'string',
            ],
            [
                [
                    'status',
                    'autoclick_ratio',
                ],
                'integer',
            ],
            [
                [
                    'title',
                ],
                'string',
                'max' => 128,
            ],
            [
                [
                    'description',
                ],
                'string',
                'max' => 255,
            ],
            [
                [
                    'link',
                ],
                'string',
                'max' => 64,
            ],
            [
                [
                    'link',
                ],
                'unique',
            ],
            [
                [
                    'autoclick_enabled',
                ],
                'boolean',
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
            'id_service' => 'Service',
            'title' => 'Title',
            'description' => 'Description',
            'link' => 'Link',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'id_lp' => 'Landing Page',
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
