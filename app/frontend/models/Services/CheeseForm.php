<?php

namespace frontend\models\Services;

use yii\base\Model;

class CheeseForm extends Model
{
    # Fields
    public $price;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'price',
                ],
                'required',
            ],
            [
                [
                    'price',
                ],
                'integer',
            ],

        ];
    }

    public function attributeLabels()
    {
        return [
            'price' => 'Price',
        ];
    }
}
