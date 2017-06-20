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
use common\models\Instances;
use common\models\Campaigns;

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

    public $instances = [];
    public $instancesById = [];
    public $instances_links = [];

    public $campaigns = [];
    public $campaigns_links = [];
    public $chart = [];
    public $struct = [];

    public function init()
    {
        $this->dateFrom = date('Y-m-d');
        $this->dateTo = date('Y-m-d');
    }

    # Campaigns
    public function getCampaignsLinks()
    {
        if (!count($this->campaigns_links)) {
            $this->instances_links = Instances::find()
                ->select([
                    "hostname",
                    "id",
                ])
                ->asArray()
                ->indexBy("id")
                ->column();

            $this->campaigns_links = Campaigns::find()
                ->select([
                    "link",
                    "title",
                    "id_old",
                ])
                ->where([
                    "id_old" => $this->getCampaigns(),
                ])
                ->indexBy("id_old")
                ->asArray()
                ->all();
        }

        return $this->campaigns_links;
    }

    public function getCampaigns()
    {
        if (!count($this->campaigns)) {
            $this->campaigns = Reports::find()
                ->select('id_campaign')
                ->groupBy('id_campaign')
                ->orderBy('id_campaign')
                ->indexBy('id_campaign')
                ->asArray()
                ->column();
        }

        return $this->campaigns;
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

    # Instances By Id
    public function getInstancesById()
    {
        if (!count($this->instancesById)) {
            $this->instancesById = Instances::find()
                ->select([
                    'id_provider',
                    'id',
                ])
                ->indexBy('id')
                ->asArray()
                ->column();
        }

        return $this->instancesById;
    }

    protected function applyFilters(Query $query)
    {
        # Provider
        if ($this->provider !== null && $this->provider !== "0") {
            $query->andWhere([
                'id_instance' => $this->getInstances()[$this->provider],
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

//        dump($query->createCommand()->getRawSql());
//        dump($this->getInstances());
//        dump($this->getInstancesById());

        return $query;
    }

    # Instances
    public function getInstances()
    {
        if (!count($this->instances)) {
            $this->instances = Instances::find()
                ->select([
                    'id',
                    'id_provider',
                ])
                ->where([
                    'status' => 1,
                ])
                ->indexBy('id_provider')
                ->asArray()
                ->column();

            $this->instancesById = Instances::find()
                ->select([
                    'id_provider',
                    'id',
                ])
                ->where([
                    'status' => 1,
                ])
                ->indexBy('id')
                ->asArray()
                ->column();
        }

        return $this->instances;
    }

    public function getStruct()
    {
        if (!count($this->struct)) {
            $struct = [];
            foreach ($this->getCountries() as $country) {
                $struct[$country['id']] = [
                    'name' => ucfirst($country['name']),
                    'items' => [],
                ];

                foreach ($this->getProviders() as $provider) {
                    if ($provider['id_country'] !== $country['id']) {
                        continue;
                    }

                    $struct[$country['id']]['items'][$provider['id']] = [
                        'name' => ucfirst($provider['name']),
                        'items' => [],
                    ];

                    foreach ($this->getOperators() as $operator) {
                        if ($operator['id_provider'] !== $provider['id']) {
                            continue;
                        }

                        $struct[$country['id']]['items'][$provider['id']]['items'][$operator['id']] = ucfirst($operator['name']);
                    }
                }
            }
            $this->struct = $struct;
        }

        return $this->struct;
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
                    'id',
                    'id_country',
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
}
