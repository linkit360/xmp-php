<?php

namespace frontend\models;

use function array_key_exists;
use function array_keys;
use function count;
use function in_array;
use const SORT_ASC;

use yii\db\Query;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;

use common\models\Countries;
use common\models\Operators;
use common\models\Providers;
use common\models\Reports;

/**
 * Reports Form
 */
class ReportsForm extends Model
{
    # Fields
    public $country;
    public $operator;
    public $provider;
    public $campaign;

    public $dateFrom;
    public $dateTo;

    # Data
    public $countries = [];

    public $operators = [];
    public $operatorsByCode = [];

    public $providers = [];
    public $providersByNames = [];
    public $providersByNamesCountry = [];

    public $campaigns = [];
    public $chart = [];
    public $struct = [];

    public function init()
    {
        $this->dateFrom = date('Y-m-d');
        $this->dateTo = date('Y-m-d');
    }

    # Campaigns
    public function getCampaigns()
    {
        if (!count($this->campaigns)) {
            $this->campaigns = [];
            $db = Reports::find()
                ->select('DISTINCT ON (id_campaign) *')
                ->all();

            /** @var Reports $report */
            foreach ($db as $report) {
                $this->campaigns[$report->id_campaign] = "" . $report->id_campaign;
            }
        }

        return $this->campaigns;
    }

    # Countries
    public function getCountries()
    {
        if (!count($this->countries)) {
            $this->countries = Countries::find()
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
                ->asArray()
                ->all();
        }

        return $this->countries;
    }

    # Providers
    public function getProviders()
    {
        if (!count($this->providers)) {
            $this->providers = Providers::find()
                ->select([
                    'name',
                    'name_alias',
                    'id',
                    'id_country',
                ])
                ->orderBy([
                    'name' => SORT_ASC,
                ])
                ->asArray()
                ->all();

            $this->providersByNames = ArrayHelper::map(
                $this->providers,
                'name',
                'name_alias'
            );

            $this->providersByNamesCountry = ArrayHelper::map(
                $this->providers,
                'name',
                'id_country'
            );
        }

        return $this->providers;
    }

    # Operators
    public function getOperators()
    {
        if (!count($this->operators)) {
            $this->operators = Operators::find()
                ->select([
                    'name',
                    'id',
                    'code',
                    'id_provider',
                ])
                ->where([
                    'status' => 1,
                ])
                ->orderBy([
                    'name' => SORT_ASC,
                ])
                ->asArray()
                ->all();


            $this->operatorsByCode = ArrayHelper::map(
                $this->operators,
                'code',
                'name'
            );
        }

        return $this->operators;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'campaign',
                    'operator',
                    'provider',
                    'country',

                    'dateFrom',
                    'dateTo',
                ],
                'string',
            ],
        ];
    }

    /**
     * @return ActiveDataProvider
     */
    public function dataProviderAd()
    {
        $query = (new Query())
            ->from('xmp_reports')
            ->select([
                'SUM(lp_hits) as lp_hits',
                'SUM(lp_msisdn_hits) as lp_msisdn_hits',
                'SUM(mo_total) as mo',
                'SUM(mo_charge_success) as mo_success',
                'SUM(renewal_charge_success) as retry_success',
                'SUM(pixels) as pixels',

                "date_trunc('day', report_at) as report_at_day",
                'id_campaign',
                'provider_name',
                'operator_code',
            ])
            ->groupBy([
                'report_at_day',
                'id_campaign',
                'provider_name',
                'operator_code',
            ])
            ->orderBy([
                'report_at_day' => SORT_DESC,
            ]);

        $query = $this->applyFilters($query);

//        dump($query->createCommand()->getRawSql());
//        dump($query->all());
//        die;

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    /**
     * @return ActiveDataProvider
     */
    public function dataConv()
    {
        $query = (new Query())
            ->from('xmp_reports')
            ->select([
                'SUM(lp_hits) as lp_hits',
                'SUM(lp_msisdn_hits) as lp_msisdn_hits',
                'SUM(mo_total) as mo',
                'SUM(mo_charge_success) as mo_success',

                "date_trunc('day', report_at) as report_at_day",
                'id_campaign',
                'provider_name',
                'operator_code',
            ])
            ->groupBy([
                'report_at_day',
                'id_campaign',
                'provider_name',
                'operator_code',
            ])
            ->orderBy([
                'report_at_day' => SORT_DESC,
            ]);

        $query = $this->applyFilters($query);

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    public function dataConvChart()
    {
        $query = (new Query())
            ->from('xmp_reports')
            ->select([
                'SUM(lp_hits) as lp_hits',
//                'SUM(lp_msisdn_hits) as lp_msisdn_hits',
//                'SUM(mo) as mo',
//                'SUM(mo_success) as mo_success',
                'id_campaign',
                "date_trunc('day', report_at) as report_at_day",
            ])
            ->groupBy([
                'id_campaign',
                'report_at_day',
            ])
            ->orderBy([
                'report_at_day' => SORT_ASC,
            ]);

        $query = $this->applyFilters($query);

        $chart = [
            'sum' => 0,
            'days' => [],
            'series' => [],
        ];

        $series = [];
        foreach ($query->all() as $row) {
//            dump($row);
            $date = date(
                'Y.m.d',
                strtotime($row['report_at_day'])
            );

            if (!in_array($date, $chart['days'])) {
                $chart['days'][] = $date;
            }

            if (!array_key_exists($row['id_campaign'], $series)) {
                $series[$row['id_campaign']] = [
                    'name' => 'Campaign #' . $row['id_campaign'],
                    'data' => [],
                ];
            }

//            $chart['series'][]['data'][] = (int)$row['lp_hits'];
            $series[$row['id_campaign']]['data'][] = (int)$row['lp_hits'];
            $chart['sum'] += $row['lp_hits'];
        }

        foreach ($series as $ser) {
            $chart['series'][] = $ser;
        }

        $this->chart = $chart;
    }

    public function dataAdChart()
    {
        $query = (new Query())
            ->from('xmp_reports')
            ->select([
//                'SUM(lp_hits) as lp_hits',
//                'SUM(lp_msisdn_hits) as lp_msisdn_hits',
//                'SUM(mo) as mo',
//                'SUM(mo_success) as mo_success',
                'SUM(pixels) as pixels',
                'id_campaign',

                "date_trunc('day', report_at) as report_at_day",
            ])
            ->groupBy([
                'id_campaign',
                'report_at_day',
            ])
            ->orderBy([
                'report_at_day' => SORT_ASC,
            ]);
        $query = $this->applyFilters($query);

        $chart = [
            'sum' => 0,
            'days' => [],
            'series' => [],
        ];

        $series = [];
        foreach ($query->all() as $row) {
            $date = date(
                'Y.m.d',
                strtotime($row['report_at_day'])
            );

            if (!in_array($date, $chart['days'])) {
                $chart['days'][] = $date;
            }

//            $chart['series'][0]['data'][] = (int)$row['lp_hits'];
//            $chart['series'][1]['data'][] = (int)$row['mo'];
//            $chart['series'][2]['data'][] = (int)$row['mo_success'];
            $chart['sum'] += $row['pixels'];

            if (!array_key_exists($row['id_campaign'], $series)) {
                $series[$row['id_campaign']] = [
                    'name' => 'Campaign #' . $row['id_campaign'],
                    'data' => [],
                ];
            }
            $series[$row['id_campaign']]['data'][] = (int)$row['pixels'];
        }

        foreach ($series as $ser) {
            $chart['series'][] = $ser;
        }

        $this->chart = $chart;
    }

    public function getStruct()
    {
        if (!count($this->struct)) {
            $struct = [];
            foreach ($this->getCountries() as $country) {
                $struct[$country['id']] = [
                    'name' => $country['name'],
                    'items' => [],
                ];

                foreach ($this->getProviders() as $provider) {
                    if ($provider['id_country'] !== $country['id']) {
                        continue;
                    }

                    $struct[$country['id']]['items'][$provider['id']] = [
                        'name' => $provider['name_alias'],
                        'items' => [],
                    ];

                    foreach ($this->getOperators() as $operator) {
                        if ($operator['id_provider'] !== $provider['id']) {
                            continue;
                        }

                        $struct[$country['id']]['items'][$provider['id']]['items'][$operator['id']] = $operator['name'];
                    }
                }
            }
            $this->struct = $struct;
        }

        return $this->struct;
    }

    private function applyFilters(Query $query)
    {
        # Provider
        if ($this->provider !== null && $this->provider !== "0") {
            $query->andWhere([
                'provider_name' => ArrayHelper::map(
                    $this->getProviders(),
                    'id',
                    'name'
                )[$this->provider],
            ]);
        }

        # Operator
        if ($this->operator !== null && $this->operator !== "0") {
            $operator = Operators::find()
                ->select('code')
                ->where([
                    'id' => $this->operator,
                ])
                ->column();

            $query->andWhere([
                'operator_code' => $operator[0],
            ]);
        } else {
            # Country
            if ($this->country !== null && $this->country !== "0") {
                $operators = [];

                foreach ($this->getStruct()[$this->country]['items'] as $provider) {
                    if (count($provider['items'])) {
                        foreach ($provider['items'] as $opId => $oper) {
                            $operators[$opId] = true;
                        }
                    }
                }

                $operators = Operators::find()
                    ->select('code')
                    ->where([
                        'id' => array_keys($operators),
                    ])
                    ->column();

                $query->andWhere([
                    'operator_code' => $operators,
                ]);
            }
        }

        # Campaign
        if ($this->campaign !== null && $this->campaign !== "0") {
            $query->andWhere([
                'id_campaign' => $this->campaign,
            ]);
        }

        # dateFrom
        if (substr_count($this->dateFrom, '-') > 1) {
            $query->andWhere(
                'report_at >= :date_from'
            )->addParams([
                'date_from' => $this->dateFrom,
            ]);
        }

        # dateTo
        if (substr_count($this->dateTo, '-') > 1) {
            $query->andWhere(
                'report_at < :date_to'
            )->addParams([
                'date_to' => date('Y-m-d', strtotime('+1 day', strtotime($this->dateTo))),
            ]);
        }

        return $query;
    }
}
