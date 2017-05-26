<?php

namespace frontend\models\Services;

use yii\base\Model;

class QrtechForm extends Model
{
    # Fields
    public $sms_on_content;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'sms_on_content',
                ],
                'required',
            ],
            [
                [
                    'sms_on_content',
                ],
                'string',
            ],

        ];
    }

    public function attributeLabels()
    {
        return [
            'sms_on_content' => 'SMS On Content',
        ];
    }
}
