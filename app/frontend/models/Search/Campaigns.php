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
                    'id_user',
                    'title',
                    'description',
                    'link',
                    'created_at',
                    'updated_at',
                    'id_operator',
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
        $query = CampaignsModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!Yii::$app->user->can('Admin')) {
            $query
                ->andFilterWhere(['id_user' => Yii::$app->user->id])
                ->andFilterWhere(['status' => 1]);
        } else {
            $query->andFilterWhere(['id_user' => $this->id_user]);
        }

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
