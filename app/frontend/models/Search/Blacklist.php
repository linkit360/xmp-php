<?php

namespace frontend\models\Search;

use yii\base\Model;
use yii\data\ActiveDataProvider;

use common\models\MsisdnBlacklist;

class Blacklist extends MsisdnBlacklist
{
    public $dateFrom;
    public $dateTo;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'msisdn',
                    'dateFrom',
                    'dateTo',
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
        $query = MsisdnBlacklist::find();

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

        $query->andFilterWhere(['id_user' => $this->id_user]);
        $query->andFilterWhere(['like', 'msisdn', $this->msisdn]);

        return $dataProvider;
    }
}
