<?php

namespace frontend\models\Search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Content\Categories as CategoriesModel;

/**
 * Categories represents the model behind the search form about `common\models\Content\Categories`.
 */
class Categories extends CategoriesModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'id_user',
                    'title',
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
        $query = CategoriesModel::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!Yii::$app->user->can('Admin')) {
            $query->andFilterWhere(['id_user' => Yii::$app->user->id]);
        } else {
            $query->andFilterWhere(['id_user' => $this->id_user]);
        }

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
