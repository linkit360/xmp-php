<?php

use yii\db\Migration;
use yii\db\Query;

class m170601_091513_aggregate_reports extends Migration
{
    public function safeUp()
    {
        $connection = $this->getDb();

        $query = (new Query())
            ->from('xmp_reports')
            ->select([
                "date_trunc('day', report_at) as report_at_day",
            ])
            ->orderBy([
                "report_at" => SORT_ASC,
            ])
            ->limit(1)
            ->column();

        if (!count($query)) {
            return true;
        }

        $lastDay = new DateTime($query[0]);

        $now = new DateTime(date('Y-m-d'));
        $now->add(new DateInterval('P1D'));
        while (true) {
            $to = $now->format('Y-m-d');
            $from = $now->sub(new DateInterval('P1D'))->format('Y-m-d');

            if ($now < $lastDay) {
                break;
            }

            $query = (new Query())
                ->from('xmp_reports')
                ->select([
                    'SUM(lp_hits) as lp_hits',
                    'SUM(lp_msisdn_hits) as lp_msisdn_hits',
                    'SUM(mo_total) as mo_total',
                    'SUM(mo_charge_success) as mo_charge_success',
                    'SUM(renewal_total) as renewal_total',
                    'SUM(pixels) as pixels',
                    'SUM(mo_charge_failed) as mo_charge_failed',
                    'SUM(mo_charge_sum) as mo_charge_sum',
                    'SUM(mo_rejected) as mo_rejected',
                    'SUM(renewal_charge_success) as renewal_charge_success',
                    'SUM(renewal_charge_sum) as renewal_charge_sum',
                    'SUM(renewal_failed) as renewal_failed',
                    'SUM(outflow) as outflow',
                    'SUM(injection_total) as injection_total',
                    'SUM(injection_charge_success) as injection_charge_success',
                    'SUM(injection_charge_sum) as injection_charge_sum',
                    'SUM(injection_failed) as injection_failed',
                    'SUM(expired_total) as expired_total',
                    'SUM(expired_charge_success) as expired_charge_success',
                    'SUM(expired_charge_sum) as expired_charge_sum',
                    'SUM(expired_failed) as expired_failed',

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
                ->where([
                    'AND',
                    "report_at >= '" . $from . "'",
                    "report_at <= '" . $to . "'",
                ]);

            $rows = $query->all();
            $cnt = count($rows);
            if ($cnt) {
                echo $from . PHP_EOL;
                echo $to . PHP_EOL;
                echo number_format($cnt) . PHP_EOL . PHP_EOL;

                foreach ($rows as $row) {
                    // prep query
                    $row['report_at'] = new DateTime($row['report_at_day']);
                    $row['report_at']->add(new DateInterval('P1D'));
                    $row['report_at']->sub(new DateInterval('PT1S'));
                    $row['report_at'] = $row['report_at']->format('Y-m-d H:i:s');
                    unset($row['report_at_day']);

                    $q = $connection->createCommand()->insert('xmp_reports', $row);

                    // remove all for day
                    $z = $connection->createCommand()
                        ->delete(
                            "xmp_reports",
                            [
                                'AND',
                                "report_at >= '" . $from . "'",
                                "report_at <= '" . $to . "'",
                            ]
                        );
                    $z->execute();

                    // add new row for 23:59:59 time
                    $q->execute();
                }
            }
        }
    }

    public function safeDown()
    {
        // no need
    }
}
