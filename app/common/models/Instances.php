<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * @property string  $id
 * @property integer $id_provider
 * @property integer $status
 */
class Instances extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'xmp_instances';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'id_provider',
                ],
                'required',
            ],
            [
                [
                    'id',
                ],
                'string',
            ],
            [
                [
                    'id_provider',
                    'status',
                ],
                'integer',
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
            'id_provider' => 'Provider ID',
            'status' => 'Status',
        ];
    }
}
