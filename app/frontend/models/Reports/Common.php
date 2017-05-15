<?php

namespace frontend\models\Reports;

use const SORT_ASC;
use function count;
use function array_keys;

use yii\db\Query;
use yii\base\Model;
use yii\helpers\ArrayHelper;

use common\models\Reports;
use common\models\Countries;
use common\models\Operators;
use common\models\Providers;

class Common extends Model
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

    protected function applyFilters(Query $query)
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
