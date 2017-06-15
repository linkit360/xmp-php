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
