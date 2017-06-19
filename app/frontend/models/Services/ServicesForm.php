<?php

namespace frontend\models\Services;

use const null;
use function array_merge_recursive;

use Yii;
use yii\helpers\ArrayHelper;

use common\models\Services;
use common\models\Content\Content;

class ServicesForm extends Services
{
    # Fields
    public $content = [];
    public $price_raw;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge_recursive(
            parent::rules(),
            [
                [
                    [
                        'content',
                        'price_raw',
                    ],
                    'required',
                ],
                [
                    [
                        'content',
                    ],
                    'safe',
                ],
                [
                    [
                        'price_raw',
                    ],
                    'double',
                    'min' => 0,
                ],
            ]
        );
    }

    public function attributeLabels()
    {
        return array_merge_recursive(
            parent::attributeLabels(),
            [
                'content' => 'Content',
                'price_raw' => 'Price',
            ]
        );
    }

    public function beforeValidate()
    {
        $this->id_content = json_encode($this->content);
        $this->price = (int)(round($this->price_raw, 2) * 100);

        return parent::beforeValidate();
    }

    public function getContentForm($countryId)
    {
        $where = [
            "status" => 1,
            "id_user" => Yii::$app->user->id,
        ];

        if (Yii::$app->user->can('Admin')) {
            unset($where["id_user"]);
        }

        $cont = Content::find()
            ->select([
                'id',
                'title',
            ])
            ->where([
                'AND',
                $where,
                [
                    'OR',
                    [
                        "blacklist" => null,
                    ],
                    [
                        'NOT',
                        'blacklist @> \'["' . (integer)$countryId . '"]\'::jsonb',
                    ],
                ],
            ])
            ->asArray()
            ->all();

        return ArrayHelper::map($cont, 'id', 'title');
    }
}
