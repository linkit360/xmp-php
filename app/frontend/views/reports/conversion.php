<?php
use kartik\export\ExportMenu;
use yii\grid\GridView;

use miloschuman\highcharts\Highcharts;
use yii\helpers\Html;

/**
 * @var yii\web\View                $this
 * @var frontend\models\ReportsForm $model
 */

$this->title = 'Conversion';
$this->params['subtitle'] = 'Report And Chart';
$this->params['breadcrumbs'][] = [
    'label' => 'Reports',
    'url' => '/reports/index',
];
$this->params['breadcrumbs'][] = $this->title;

$excludeColums = [
    'id_campaign',
    'operator_code',
];

$total = [
    'lp_hits' => 0,
    'mo' => 0,
    'mo_uniq' => 0,
    'mo_success' => 0,
    'pixels' => 0,
    'lp_msisdn_hits' => 0,
];

$dp = $model->dataConv();
if (!empty($dp->getModels())) {
    foreach ($dp->getModels() as $row) {
        foreach ($row as $key => $val) {
            if (in_array($key, $excludeColums) || !is_numeric($val)) {
                continue;
            }

            $total[$key] += $val;
        }
    }
}

$gridColumns = [
    [
        'attribute' => 'report_date',
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
        'contentOptions' => function () {
            return ['class' => 'text-right'];
        },
        'content' => function ($data) {
            return number_format($data['lp_hits']);
        },
        'footerOptions' => [
            'class' => 'text-right',
            'style' => 'font-weight: bold;',
        ],
        'footer' => number_format($total['lp_hits']),
    ],
    /*
    [
        'attribute' => 'lp_msisdn_hits',
        'contentOptions' => function () {
            return ['class' => 'text-right'];
        },
        'content' => function ($data) {
            return number_format($data['lp_msisdn_hits']);
        },
    ],
    */
    [
        'attribute' => 'mo',
        'label' => 'MO',
        'contentOptions' => function () {
            return ['class' => 'text-right'];
        },
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
        'contentOptions' => function () {
            return ['class' => 'text-right'];
        },
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
        'label' => 'Conversion Rate',
        'contentOptions' => function () {
            return [
                'class' => 'text-right',
                'style' => 'background-color: #b3e6ff',
            ];
        },
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
                Total Lp Hits For Period: <?= $model->chart['sum'] ?>
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
