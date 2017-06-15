<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

use common\models\Operators;

class OperatorsForm extends Operators
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_provider', 'code', 'status'], 'integer'],
            [['name', 'isp', 'msisdn_prefix', 'mcc', 'mnc', 'created_at'], 'safe'],
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
        $query = Operators::find();

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
            'id' => $this->id,
            'id_provider' => $this->id_provider,
            'code' => $this->code,
            'status' => 1,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'isp', $this->isp])
            ->andFilterWhere(['like', 'msisdn_prefix', $this->msisdn_prefix])
            ->andFilterWhere(['like', 'mcc', $this->mcc])
            ->andFilterWhere(['like', 'mnc', $this->mnc]);

        return $dataProvider;
    }
}
