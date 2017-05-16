<?php
use yii\grid\GridView;
use yii\helpers\Html;

use kartik\export\ExportMenu;
use miloschuman\highcharts\Highcharts;

/**
 * @var yii\web\View                          $this
 * @var frontend\models\Reports\RevenueReport $model
 */

$this->title = 'Revenue';
$this->params['subtitle'] = 'Report And Chart';
$this->params['breadcrumbs'][] = [
    'label' => 'Reports',
    'url' => '/reports/index',
];
$this->params['breadcrumbs'][] = $this->title;

$excludeColums = [
    'id_campaign',
    'operator_code',
    'mo_charge_sum',
    'renewal_charge_sum',
];

$total = [];
$dp = $model->data();
if (!empty($dp->getModels())) {
    foreach ($dp->getModels() as $row) {
        foreach ($row as $key => $val) {
            if (in_array($key, $excludeColums) || !is_numeric($val)) {
                continue;
            }

            if (!array_key_exists($key, $total)) {
                $total[$key] = 0;
            }

            $total[$key] += $val;
        }
    }
}

$gridColumns = [
    [
        'attribute' => 'report_date',
        'headerOptions' => [
            'class' => 'text-right',
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'contentOptions' => [
            'class' => 'text-right',
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'content' => function ($data) {
            return date('Y-m-d', strtotime($data['report_at_day']));
        },
    ],
    [
        'attribute' => 'id_campaign',
        'contentOptions' => function () {
            return ['class' => 'text-right'];
        },
        'content' => function ($data) {
            return number_format($data['id_campaign']);
        },
    ],
    [
        'label' => 'Country',
        'content' => function ($data) use ($model) {
            return $model->countries[$model->providersByNamesCountry[$data['provider_name']]]['name'];
        },
    ],
    [
        'attribute' => 'id_provider',
        'label' => 'Provider',
        'content' => function ($data) use ($model) {
            if (array_key_exists($data['provider_name'], $model->providersByNames)) {
                return $model->providersByNames[$data['provider_name']];
            }
            return '';
        },
    ],
    [
        'attribute' => 'id_operator',
        'label' => 'Operator',
        'content' => function ($data) use ($model) {
            if (array_key_exists($data['operator_code'], $model->operatorsByCode)) {
                return $model->operatorsByCode[$data['operator_code']];
            }
            return '';
        },
    ],
    [
        'attribute' => 'lp_hits',
        'label' => 'LP Hits',
        'headerOptions' => [
            'class' => 'text-right',
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'contentOptions' => [
            'class' => 'text-right',
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'content' => function ($data) {
            return number_format($data['lp_hits']);
        },
        'footerOptions' => [
            'class' => 'text-right',
            'style' => 'font-weight: bold;',
        ],
        'footer' => number_format($total['lp_hits']),
    ],
    [
        'attribute' => 'mo',
        'label' => 'MO Total',
        'headerOptions' => [
            'class' => 'text-right',
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'contentOptions' => [
            'class' => 'text-right',
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'content' => function ($data) {
            return number_format($data['mo']);
        },
        'footerOptions' => [
            'class' => 'text-right',
            'style' => 'font-weight: bold;',
        ],
        'footer' => number_format($total['mo']),
    ],
    [
        'attribute' => 'mo_success',
        'label' => 'MO Success',
        'headerOptions' => [
            'class' => 'text-right',
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'contentOptions' => [
            'class' => 'text-right',
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'content' => function ($data) {
            return number_format($data['mo_success']);
        },
        'footerOptions' => [
            'class' => 'text-right',
            'style' => 'font-weight: bold;',
        ],
        'footer' => number_format($total['mo_success']),
    ],
    [
        'attribute' => 'mo_charge_failed',
        'label' => 'MO Failed',
        'headerOptions' => [
            'class' => 'text-right',
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'contentOptions' => [
            'class' => 'text-right',
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'content' => function ($data) {
            return number_format($data['mo_charge_failed']);
        },
        'footerOptions' => [
            'class' => 'text-right',
            'style' => 'font-weight: bold;',
        ],
        'footer' => number_format($total['mo_charge_failed']),
    ],
    [
        'attribute' => 'mo_rejected',
        'label' => 'MO Rejected',
        'headerOptions' => [
            'class' => 'text-right',
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'contentOptions' => [
            'class' => 'text-right',
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'content' => function ($data) {
            return number_format($data['mo_rejected']);
        },
        'footerOptions' => [
            'class' => 'text-right',
            'style' => 'font-weight: bold;',
        ],
        'footer' => number_format($total['mo_rejected']),
    ],
    [
        'attribute' => 'outflow',
        'label' => 'Outflow',
        'headerOptions' => [
            'class' => 'text-right',
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'contentOptions' => [
            'class' => 'text-right',
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'content' => function ($data) {
            return number_format($data['outflow']);
        },
        'footerOptions' => [
            'class' => 'text-right',
            'style' => 'font-weight: bold;',
        ],
        'footer' => number_format($total['outflow']),
    ],
    [
        'attribute' => 'renewal_total',
        'label' => 'Renewal Total',
        'headerOptions' => [
            'class' => 'text-right',
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'contentOptions' => [
            'class' => 'text-right',
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'content' => function ($data) {
            return number_format($data['renewal_total']);
        },
        'footerOptions' => [
            'class' => 'text-right',
            'style' => 'font-weight: bold;',
        ],
        'footer' => number_format($total['renewal_total']),
    ],
    [
        'attribute' => 'renewal_charge_success',
        'label' => 'Renewal Success',
        'headerOptions' => [
            'class' => 'text-right',
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'contentOptions' => [
            'class' => 'text-right',
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'content' => function ($data) {
            return number_format($data['renewal_charge_success']);
        },
        'footerOptions' => [
            'class' => 'text-right',
            'style' => 'font-weight: bold;',
        ],
        'footer' => number_format($total['renewal_charge_success']),
    ],
    [
        'attribute' => 'renewal_failed',
        'label' => 'Renewal Failed',
        'headerOptions' => [
            'class' => 'text-right',
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'contentOptions' => [
            'class' => 'text-right',
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'content' => function ($data) {
            return number_format($data['renewal_failed']);
        },
        'footerOptions' => [
            'class' => 'text-right',
            'style' => 'font-weight: bold;',
        ],
        'footer' => number_format($total['renewal_failed']),
    ],
    [
        'label' => 'Conversion Rate',
        'headerOptions' => [
            'class' => 'text-right',
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'contentOptions' => [
            'class' => 'text-right',
            'style' => 'width: 1%; white-space: nowrap; background-color: #b3e6ff;',

        ],
        'content' => function ($data) {
            $conv = "0.00";
            if ($data['lp_hits'] > 0) {
                $conv = number_format(
                    $data['mo'] / $data['lp_hits'] * 100,
                    2
                );
            }

            return '<b>' . $conv . '</b>%';
        },
    ],
    [
        'label' => 'Revenue',
        'headerOptions' => [
            'class' => 'text-right',
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'contentOptions' => [
            'class' => 'text-right',
            'style' => 'width: 1%; white-space: nowrap; background-color: #b3e6ff;',

        ],
        'content' => function ($data) {
            return '<b>' . number_format(floor($data['revenue'] / 100)) . '</b>';
        },
        'footerOptions' => [
            'class' => 'text-right',
            'style' => 'font-weight: bold;',
        ],
        'footer' => number_format(floor($total['revenue'] / 100)),
    ],
];
?>
<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-title">
            <h5>
                Filter
            </h5>
        </div>

        <div class="ibox-content">
            <?php
            echo $this->render(
                'filter',
                [
                    'model' => $model,
                ]
            );
            ?>
        </div>
    </div>
</div>

<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-title">
            <h5>
                Total Revenue For Period: <?= $model->chart['sum'] ?>
            </h5>
        </div>

        <div class="ibox-content">
            <?php
            if (array_key_exists('sum', $model->chart) && $model->chart['sum'] > 0) {
                echo Highcharts::widget([
                    'options' => [
                        'chart' => [
                            'height' => 235,
                            'zoomType' => 'x',
                            'type' => 'column',
                        ],
                        'title' => [
                            'useHTML' => true,
                            'text' => '',
                        ],
                        'plotOptions' => [
                            'series' => [
                                'fillOpacity' => 0.7,
                                'lineWidth' => 2,
                            ],
                        ],
                        'xAxis' => [
                            'gridLineColor' => '#BFC8CE',
                            'gridLineDashStyle' => 'shortdash',
                            'gridLineWidth' => '1',
                            'min' => 0,
                            'tickmarkPlacement' => 'on',
                            'categories' => $model->chart['days'],
                        ],
                        'yAxis' => [
                            'gridLineColor' => '#BFC8CE',
                            'gridLineDashStyle' => 'shortdash',
                            'gridLineWidth' => '1',
                            'title' => ['text' => 'Lp Hits'],
                        ],
                        'credits' => ['enabled' => false],
                        'series' => $model->chart['series'],
                    ],
                ]);
            } else {
                echo '<div style="text-align: center;">No data.</div>';
            }
            ?>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="ibox">
        <div class="ibox-content">
            <?php
            echo Html::tag(
                'div',
                ExportMenu::widget([
                    'dataProvider' => $dp,
                    'columns' => $gridColumns,
                    'fontAwesome' => true,
                    'dropdownOptions' => [
                        'label' => 'Export All',
                        'class' => 'btn btn-default',
                    ],
                ]),
                [
                    'class' => 'pull-right',
                ]
            );

            echo GridView::widget([
                'dataProvider' => $dp,
                'showFooter' => true,
                'columns' => $gridColumns,
            ]);
            ?>
        </div>
    </div>
</div>
