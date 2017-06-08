<?php

namespace frontend\models\Instances;

use common\models\Providers;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Instances as InstancesModel;

/**
 * Instances represents the model behind the search form about `common\models\Instances`.
 */
class SearchForm extends InstancesModel
{
    public $providers = [];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'safe'],
            [['id_provider', 'status'], 'integer'],
        ];
    }


    # Providers
    public function getProviders()
    {
        if (!count($this->providers)) {
            $this->providers = Providers::find()
                ->select([
                    'name',
                    'id',
                ])
                ->orderBy([
                    'name' => SORT_ASC,
                ])
                ->indexBy('id')
                ->asArray()
                ->column();
        }

        return $this->providers;
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
        $query = InstancesModel::find();

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
            'id_provider' => $this->id_provider,
            'status' => 1,
            'id' => $this->id,
        ]);

        return $dataProvider;
    }
}
