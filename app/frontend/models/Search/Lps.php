<?php

namespace frontend\models\Search;

use const SORT_ASC;
use const SORT_DESC;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use common\models\Lps as LpsModel;

class Lps extends LpsModel
{
    public $date_range;

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
                    'date_range',
                    'id_user',
                ],
                'safe',
            ],
            [
                [
                    'status',
                ],
                'integer',
            ],
        ];
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
        $query = LpsModel::find()
            ->orderBy([
                'created_at' => SORT_DESC,
            ]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        if ($this->date_range) {
            $time = explode(' - ', $this->date_range);

            $query
                ->andFilterWhere([
                    '>',
                    'created_at',
                    $time[0],
                ])
                ->andFilterWhere([
                    '<',
                    'created_at',
                    $time[1],
                ]);
        }

        if (!Yii::$app->user->can('Admin')) {
            $query->andFilterWhere(['id_user' => Yii::$app->user->id]);
        } else {
            $query->andFilterWhere(['id_user' => $this->id_user]);
        }

        $query
            ->andFilterWhere(['status' => 1])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
