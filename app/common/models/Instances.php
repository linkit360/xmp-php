<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * @property string  $id
 * @property integer $id_provider
 * @property integer $status
 * @property string  $title
 * @property string  $hostname
 */
class Instances extends ActiveRecord
{
    public static function tableName()
    {
        return "xmp_instances";
    }

    public function rules()
    {
        return [
            [
                [
                    "id_provider",
                ],
                "required",
            ],
            [
                [
                    "id",
                    "title",
                    "hostname",
                ],
                "string",
            ],
            [
                [
                    "id_provider",
                    "status",
                ],
                "integer",
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_provider' => 'Provider ID',
            'status' => 'Status',
            'title' => 'Title',
            'hostname' => 'Hostname',
        ];
    }
}
