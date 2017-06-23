<?php

namespace frontend\models\Search;

use function array_keys;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use common\models\Providers;
use common\models\Services as ServicesModel;

/**
 * Services represents the model behind the search form about `common\models\Services`.
 */
class Services extends ServicesModel
{
    public $date_range;
    public $id_country;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'title',
                    'description',
                    'id_service',
                    'id_content',
                    'id_country',
                    'id_user',
                    'service_opts',
                    'date_range',
                ],
                'safe',
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


    public function attributeLabels()
    {
        return array_merge(
            parent::attributeLabels(),
            [
                'id_country' => 'Country',
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ServicesModel::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'time_create' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->id_country) {
            $prov = Providers::find()
                ->select('id')
                ->where([
                    'id_country' => $this->id_country,
                ])
                ->indexBy('id')
                ->asArray()
                ->all();

            $query->andFilterWhere([
                'id_provider' => array_keys($prov),
            ]);
        } else {
            $query->andFilterWhere([
                'id_provider' => $this->id_provider,
            ]);
        }

        // grid filtering conditions
        if ($this->date_range) {
            $time = explode(' - ', $this->date_range);

            $query
                ->andFilterWhere([
                    '>',
                    'time_create',
                    $time[0],
                ])
                ->andFilterWhere([
                    '<',
                    'time_create',
                    $time[1],
                ]);
        }

        if (!Yii::$app->user->can('Admin')) {
            $query
                ->andFilterWhere(['id_user' => Yii::$app->user->id])
                ->andFilterWhere(['status' => 1]);
        } else {
            $query->andFilterWhere(['id_user' => $this->id_user]);
        }

        $query->andFilterWhere([
            'id_service' => $this->id_service,
            'id_content' => $this->id_content,
        ]);

        $query
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
