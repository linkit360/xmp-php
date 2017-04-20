<?php

namespace frontend\models;

use function array_merge_recursive;
use common\models\Content\Content;
use common\models\Services;
use const null;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Services Form
 */
class ServicesForm extends Services
{
    # Fields
    public $content = [];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge_recursive(
            parent::rules(),
            [
                [
                    ['content'],
                    'required',
                ],
                [
                    ['content'],
                    'safe',
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
            ]
        );
    }

    public function beforeValidate()
    {
        $this->id_content = json_encode($this->content);
        return parent::beforeValidate();
    }

    public function getContentForm($countryId)
    {
        $cont = Content::find()
            ->select(
                [
                    'id',
                    'title',
                ]
            )
            ->where(
                [
                    'AND',
                    [
                        'id_user' => Yii::$app->user->id,
                    ],
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
                ]
            )
            ->asArray()
            ->all();

        $cont = ArrayHelper::map($cont, 'id', 'title');
        return $cont;
    }
}
