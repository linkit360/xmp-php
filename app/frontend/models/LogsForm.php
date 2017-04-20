<?php

namespace frontend\models;

use function array_key_exists;
use function explode;
use const false;
use const SORT_DESC;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Logs;

class LogsForm extends Logs
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
                    'id_user',
                    'controller',
                    'action',
                    'date_range',
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
        $query = Logs::find()
            ->orderBy([
                'time' => SORT_DESC,
            ]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $userreset = true;
        $user = '';
        if (array_key_exists('LogsForm', $params) && array_key_exists('id_user', $params['LogsForm'])) {
            $userreset = false;
            $user = $params['LogsForm']['id_user'];
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        if ($userreset) {
            unset($this->id_user);
        } else {
            $this->id_user = $user;
            if ($this->id_user) {
                $query->andFilterWhere(['id_user' => $this->id_user]);
            }
        }

        if ($this->date_range) {
            $time = explode(' - ', $this->date_range);

            $query
                ->andFilterWhere([
                    '>',
                    'time',
                    $time[0],
                ])
                ->andFilterWhere([
                    '<',
                    'time',
                    $time[1],
                ]);
        }

        $query
            ->andFilterWhere(['controller' => $this->controller])
            ->andFilterWhere(['action' => $this->action]);

        return $dataProvider;
    }
}
