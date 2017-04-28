<?php

namespace frontend\models\Search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Campaigns as CampaignsModel;

/**
 * Campaigns represents the model behind the search form about `common\models\Campaigns`.
 */
class Campaigns extends CampaignsModel
{
    public $id_country;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'id_service',
                    'id_lp',
                    'title',
                    'description',
                    'link',
                    'created_at',
                    'updated_at',
                ],
                'safe',
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
        $query = CampaignsModel::find();

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
        $query->andFilterWhere([
            'id_operator' => $this->id_operator,
            'status' => 1,
        ]);

        $query
            ->andFilterWhere(['id_user' => Yii::$app->user->id])
            ->andFilterWhere(['like', 'id_service', $this->id_service])
            ->andFilterWhere(['like', 'id_lp', $this->id_lp])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'link', $this->link]);

        return $dataProvider;
    }
}
