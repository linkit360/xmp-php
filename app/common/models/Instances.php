<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * @property string  $id
 * @property integer $id_operator
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
                    'id_operator',
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
                    'id_operator',
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
            'id_operator' => 'Id Operator',
            'status' => 'Status',
        ];
    }
}
