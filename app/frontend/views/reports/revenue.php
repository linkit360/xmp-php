<?php
use yii\grid\GridView;
use yii\helpers\Html;

use kartik\export\ExportMenu;
use miloschuman\highcharts\Highcharts;

/**
 * @var yii\web\View                          $this
 * @var frontend\models\Reports\RevenueReport $model
 */

$colors = [
    'total' => '#006699',
    'success' => '#069306',
    'failed' => '#ac0606',
    'rejected' => '#cca300',
    'footer' => '#d9d9d9',
];

$this->title = 'Revenue';
$this->params['subtitle'] = 'Report And Chart';
$this->params['breadcrumbs'][] = [
    'label' => 'Reports',
    'url' => '/reports/index',
];
$this->params['breadcrumbs'][] = $this->title;

function colors($name, $data, $longest, $colors, $footer = false)
{
    $pad_string = "~";

    // Row
    $output = "";
    foreach ($data as $color => $number) {
        $output .= Html::tag(
            'span',
            str_pad($number, $longest, $pad_string, STR_PAD_LEFT),
            [
                'style' => 'color: ' . $colors[$color],
            ]
        );
    }

    $cl = !$footer ? "white" : $colors['footer'];
    $output = str_replace(
        '~',
        Html::tag('span', '0', ['style' => 'color: ' . $cl]),
        $output
    );

    // Tooltip
    ob_start();
    foreach ($data as $color => $number) {
        ?>
        <tr>
            <td class='nw'>
                <?= $name . '&nbsp;' . ucfirst($color) ?>:
            </td>

            <td class='nw2' style='color: <?= $colors[$color] ?>'>
                <?= $number ?>
            </td>
        </tr>
        <?php
    }
    $tooltip = "<table style='width: 1%;' class='table'>" . ob_get_contents() . "</table>";
    ob_end_clean();

    return '<span class="po" data-container="body" data-placement="top" data-content="' . $tooltip . '">' . $output . '</span>';
}

$js = <<<JS
        $('.po').popover({
            html: true,
            trigger: 'hover focus'
        });
JS;
$this->registerJs($js, $this::POS_LOAD);

$excludeColums = [
    'id_campaign',
    'operator_code',
    'mo_charge_sum',
    'renewal_charge_sum',
];

$total = [
    'lp_hits' => 0,
    'mo' => [
        'mo_total' => 0,
        'mo_success' => 0,
        'mo_charge_failed' => 0,
        'mo_rejected' => 0,
    ],
    'outflow' => 0,
    'renewal' => [
        'renewal_total' => 0,
        'renewal_charge_success' => 0,
        'renewal_failed' => 0,
    ],
    'revenue' => 0,
    'injection' => [
        'injection_charge_success' => 0,
        'injection_failed' => 0,
    ],
];

$spec = [
    'mo',
    'renewal',
    'injection',
];

$dp = $model->data();
if (!empty($dp->getModels())) {
    foreach ($dp->getModels() as $row) {
        foreach ($row as $key => $val) {
            if (in_array($key, $excludeColums) || !is_numeric($val)) {
                continue;
            }

            // spec
            foreach ($spec as $skey) {
                if (array_key_exists($key, $total[$skey])) {
                    $total[$skey][$key] += $val;
                    continue;
                }
            }

            if (!array_key_exists($key, $total)) {
                $total[$key] = 0;
            }

            $total[$key] += $val;
        }
    }
}

// MO
$total['mo']['total'] = number_format($total['mo']['mo_total']);
$total['mo']['success'] = number_format($total['mo']['mo_success']);
$total['mo']['failed'] = number_format($total['mo']['mo_charge_failed']);
$total['mo']['rejected'] = number_format($total['mo']['mo_rejected']);
unset($total['mo']['mo_total'], $total['mo']['mo_success'], $total['mo']['mo_charge_failed'], $total['mo']['mo_rejected']);

// Renewal
$total['renewal']['total'] = number_format($total['renewal']['renewal_total']);
$total['renewal']['success'] = number_format($total['renewal']['renewal_charge_success']);
$total['renewal']['failed'] = number_format($total['renewal']['renewal_failed']);
unset($total['renewal']['renewal_total'], $total['renewal']['renewal_charge_success'], $total['renewal']['renewal_failed']);

// Injection
$total['injection']['success'] = number_format($total['injection']['injection_charge_success']);
$total['injection']['failed'] = number_format($total['injection']['injection_failed']);
unset($total['injection']['injection_charge_success'], $total['injection']['injection_failed']);

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
        'footerOptions' => [
            'class' => 'text-right',
            'style' => 'background-color: #d9d9d9;',
        ],
    ],
    [
        'attribute' => 'id_campaign',
        'label' => 'Campaign',
        'content' => function ($data) {
            return number_format($data['id_campaign']);
        },
        'footerOptions' => [
            'class' => 'text-right',
            'style' => 'background-color: #d9d9d9;',
        ],
    ],
    [
        'label' => 'Country',
        'content' => function ($row) use ($model) {
            if (in_array($row["id_instance"], $model->getInstances())) {
                $prov = $model->instancesById[$row['id_instance']];

                return $model->countries[$model->providers[$prov]['id_country']]['name'];
            }

            return '';
        },
        'footerOptions' => [
            'class' => 'text-right',
            'style' => 'background-color: #d9d9d9;',
        ],
    ],
    [
        'label' => 'Provider',
        'content' => function ($row) use ($model) {
            if (in_array($row["id_instance"], $model->getInstances())) {
                $prov = $model->instancesById[$row['id_instance']];

                return $model->providers[$prov]['name'];
            }

            return '';
        },
        'footerOptions' => [
            'class' => 'text-right',
            'style' => 'background-color: #d9d9d9;',
        ],
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
        'footerOptions' => [
            'class' => 'text-right',
            'style' => 'background-color: #d9d9d9;',
        ],
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
            'style' => 'width: 1%;',
        ],
        'content' => function ($data) {
            return number_format($data['lp_hits']);
        },
        'footerOptions' => [
            'class' => 'text-right',
            'style' => 'background-color: #d9d9d9;',
        ],
        'footer' => number_format($total['lp_hits']),
    ],
    [
        'label' => 'MO',
        'headerOptions' => [
            'style' => 'width: 1%; white-space: nowrap;',
            'class' => 'text-center',
        ],
        'contentOptions' => [
            'class' => 'text-right',
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'content' => function ($row) use ($colors) {
            $data = [];
            $data['total'] = number_format($row['mo_total']);
            $data['success'] = number_format($row['mo_success']);
            $data['failed'] = number_format($row['mo_charge_failed']);
            $data['rejected'] = number_format($row['mo_rejected']);

            return colors('MO', $data, 6, $colors);
        },
        'footerOptions' => [
            'class' => 'text-right',
            'style' => 'background-color: #d9d9d9;',
        ],
        'footer' => colors('MO', $total['mo'], 6, $colors, true),
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
            'style' => 'background-color: #d9d9d9;',
        ],
        'footer' => number_format($total['outflow']),
    ],
    [
        'label' => 'Renewal',
        'headerOptions' => [
            'class' => 'text-center',
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'contentOptions' => [
            'class' => 'text-right',
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'content' => function ($row) use ($colors) {
            $data = [];
            $data['total'] = number_format($row['renewal_total']);
            $data['success'] = number_format($row['renewal_charge_success']);
            $data['failed'] = number_format($row['renewal_failed']);

            return colors('Renewal', $data, 6, $colors);
        },
        'footerOptions' => [
            'class' => 'text-right',
            'style' => 'background-color: #d9d9d9;',
        ],
        'footer' => colors('Renewal', $total['renewal'], 6, $colors, true),
    ],
    [
        'attribute' => 'injection_charge_success',
        'label' => 'Injections',
        'headerOptions' => [
            'class' => 'text-center',
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'contentOptions' => [
            'class' => 'text-right',
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'content' => function ($row) use ($colors) {
            $data = [];
            $data['success'] = number_format($row['injection_charge_success']);
            $data['failed'] = number_format($row['injection_failed']);

            return colors('Injections', $data, 6, $colors);
        },
        'footerOptions' => [
            'class' => 'text-right',
            'style' => 'background-color: #d9d9d9;',
        ],
        'footer' => colors('Injections', $total['injection'], 6, $colors, true),
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
                    $data['mo_total'] / $data['lp_hits'] * 100,
                    2
                );
            }

            return '<b>' . $conv . '</b>%';
        },
        'footerOptions' => [
            'class' => 'text-right',
            'style' => 'background-color: #d9d9d9;',
        ],
    ],
    [
        'label' => 'Revenue',
        'headerOptions' => [
            'class' => 'text-right',
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'contentOptions' => [
            'class' => 'text-right',
            'style' => 'width: 1%; white-space: nowrap; background-color: #cefdce;',

        ],
        'content' => function ($data) {
            return '<b>' . number_format(floor($data['revenue'] / 100)) . '</b>';
        },
        'footerOptions' => [
            'class' => 'text-right',
            'style' => 'background-color: #d9d9d9;',
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
                            'title' => ['text' => 'Total Revenue'],
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
        <div class="ibox-content" style="overflow: auto;">
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
                'tableOptions' => [
                    'class' => 'table table-bordered differentTable',
                ],
            ]);
            ?>
        </div>
    </div>
</div>

<!--suppress CssUnusedSymbol -->
<style type="text/css">
    .nw, .nw2 {
        white-space: nowrap;
    }

    .nw2 {
        text-align: right;
    }

    .differentTable {
        border-collapse: inherit;
    }
</style>
