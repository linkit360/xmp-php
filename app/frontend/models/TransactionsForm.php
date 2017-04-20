<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use common\models\Countries;

/**
 * Logs Form
 */
class TransactionsForm extends Model
{
    # Fields
    public $msisdn;
    public $date;
    public $country;

    # Data
    public $countries = [];

    public function init()
    {
//        $this->date = date('Y-m-d');
        $this->date = '2016-12-22';

        # Countries
        $this->countries = [0 => 'All'] +
            Countries::find()
                ->select([
                    'name',
                    'id',
                ])
                ->where([
                    'status' => 1,
                ])
                ->orderBy([
                    'name' => SORT_ASC,
                ])
                ->indexBy('id')
                ->column();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'msisdn',
                    'country',
                    'date',
                ],
                'string',
            ],

        ];
    }

    /**
     * @return ActiveDataProvider
     */
    public function dataProvider()
    {
        $query = (new Query())
            ->from('xmp_transactions')
            ->select([

            ])
            ->orderBy([
                'sent_at' => SORT_DESC,
            ]);

        if ($this->msisdn !== null && $this->msisdn !== "") {
            $query->andWhere([
                'msisdn' => $this->msisdn,
            ]);
        }

        if ($this->country !== null && $this->country !== "0") {
            $query->andWhere([
                'id_country' => (int)$this->country,
            ]);
        }

        # dateFrom
        if (substr_count($this->date, '-') > 1) {
            $query->andWhere(
                'sent_at::date = :datee'
            )->addParams([
                'datee' => $this->date,
            ]);
        }

//        dump($query->createCommand()->getRawSql());
//        dump($query->all());
//        die;

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }
}
