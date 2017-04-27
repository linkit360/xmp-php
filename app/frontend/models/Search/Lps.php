<?php

namespace frontend\models\Search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Lps as LpsModel;

/**
 * Lps represents the model behind the search form about `common\models\Lps`.
 */
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
        $query = LpsModel::find();

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

        $query
            ->andFilterWhere(['id_user' => Yii::$app->user->id])
            ->andFilterWhere(['status' => 1])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
