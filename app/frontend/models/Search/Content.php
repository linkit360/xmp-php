<?php

namespace frontend\models\Search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use common\models\Content\Content as ContentModel;

class Content extends ContentModel
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
                    'id_category',
                    'id_publisher',
                    'title',
                    'date_range',
                    'id_user',
                ],
                'safe',
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
        $query = Content::find();

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
            $query->andFilterWhere(['id_user' => Yii::$app->user->id]);
        } else {
            $query->andFilterWhere(['id_user' => $this->id_user]);
        }

        $query
            ->andFilterWhere(['status' => 1])
            ->andFilterWhere(['id_category' => $this->id_category])
            ->andFilterWhere(['id_publisher' => $this->id_publisher])
            ->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
