<?php

namespace frontend\models\Reports;

use const SORT_ASC;
use function in_array;
use function array_key_exists;

use yii\db\Query;
use yii\data\ActiveDataProvider;

class RevenueReport extends Common
{
    /**
     * @return ActiveDataProvider
     */
    public function data()
    {
        $query = (new Query())
            ->from('xmp_reports')
            ->select([
                'SUM(lp_hits) as lp_hits',
                'SUM(lp_msisdn_hits) as lp_msisdn_hits',
                'SUM(mo_total) as mo',
                'SUM(mo_charge_success) as mo_success',
                'SUM(renewal_charge_success) as renewal_charge_success',
                'SUM(renewal_failed) as renewal_failed',
                'SUM(outflow) as outflow',

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

        $query = parent::applyFilters($query);

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    public function dataChart()
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
}
