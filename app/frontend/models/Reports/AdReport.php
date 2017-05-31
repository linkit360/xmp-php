<?php

namespace frontend\models\Reports;

use const SORT_ASC;
use function in_array;
use function array_key_exists;

use yii\db\Query;
use yii\data\ActiveDataProvider;

class AdReport extends Common
{
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
                'id_instance',
                'operator_code',
            ])
            ->groupBy([
                'report_at_day',
                'id_campaign',
                'id_instance',
                'operator_code',
            ])
            ->orderBy([
                'report_at_day' => SORT_DESC,
            ]);

        $query = parent::applyFilters($query);

//        dump($query->createCommand()->getRawSql());
//        dump($query->all());
//        die;

        return new ActiveDataProvider([
            'query' => $query,
        ]);
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
}
