<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\daterange\DateRangePicker;

use common\models\Logs;
use common\models\Users;

/**
 * @var yii\web\View                $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'Action Logs';
$this->params['subtitle'] = 'User actions';
$this->params['breadcrumbs'][] = $this->title;

$formData = [];
$formData['controllers'] = Logs::find()
    ->select('controller')
    ->groupBy('controller')
    ->indexBy('controller')
    ->column();

$formData['actions'] = Logs::find()
    ->select('action')
    ->groupBy('action')
    ->indexBy('action')
    ->column();

$formData['users'] = Users::find()
    ->select([
        'username',
        'id',
    ])
    ->indexBy('id')
    ->column();
?>
<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-content">
            <?php
            $form = ActiveForm::begin([
                'action' => ['logs'],
                'method' => 'get',
            ]);

            echo $form->field($model, 'controller')->widget(
                Select2::classname(),
                [
                    'data' => $formData['controllers'],
                    'options' => [
                        'placeholder' => 'Controller',
                    ],
                ]
            );

            echo $form->field($model, 'action')->widget(
                Select2::classname(),
                [
                    'data' => $formData['actions'],
                    'options' => [
                        'placeholder' => 'Action',
                    ],
                ]
            );

            echo $form->field($model, 'id_user')->widget(
                Select2::classname(),
                [
                    'data' => $formData['users'],
                    'options' => [
                        'placeholder' => 'User',
                    ],
                ]
            );

            echo $form->field($model, 'date_range')
                ->widget(
                    DateRangePicker::classname(),
                    [
                        'convertFormat' => true,
                        'options' => [
                            'class' => 'form-control',
                            'placeholder' => 'Date/Time',
                        ],
                        'pluginOptions' => [
                            'timePicker' => true,
                            'timePickerIncrement' => 15,
                            'locale' => ['format' => 'Y-m-d h:i A'],
                        ],
                    ]
                );

            echo '<br/>';
            echo Html::submitButton('Search', ['class' => 'btn btn-primary']);
            ActiveForm::end();
            ?>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="ibox">
        <div class="ibox-content">
            <?php
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'attribute' => 'time',
                        'contentOptions' => function () {
                            return [
                                'style' => 'width: 1%; white-space: nowrap;',
                            ];
                        },
                    ],
                    [
                        'label' => 'User',
                        'contentOptions' => function () {
                            return [
                                'style' => 'width: 1%; white-space: nowrap;',
                            ];
                        },
                        'content' => function ($data) {
                            return Html::a($data['user']['username'], '/users/' . $data['user']['id']);
                        },
                    ],
                    [
                        'attribute' => 'controller',
                        'contentOptions' => function () {
                            return [
                                'style' => 'width: 1%; white-space: nowrap;',
                            ];
                        },
                    ],
                    [
                        'attribute' => 'action',
                        'contentOptions' => function () {
                            return [
                                'style' => 'width: 1%; white-space: nowrap;',
                            ];
                        },
                    ],
                    [
                        'attribute' => 'event',
                        'content' => function ($data) {
                            $event = json_decode($data['event'], true);
                            if ($event === null) {
                                return '';
                            }

                            $table = '';
                            if (array_key_exists('id', $event)) {
                                $table .= 'ID: ' . $event['id'] . '<br/>';
                            }

                            if (array_key_exists('oldname', $event)) {
                                $table .= 'Old Name: ' . $event['oldname'] . '<br/>';
                            }

                            if (array_key_exists('ips', $event)) {
                                $table .= 'IPs: ';
                                foreach ($event['ips'] as $ip) {
                                    $table .= $ip . ' ';
                                }
                                $table .= '<br/>';
                            }

                            if (array_key_exists('fields', $event)) {
                                $table .= '<table class="table table-condensed" style="width: 1%;">';

                                foreach ($event['fields'] as $attr => $field) {
                                    $table .= '<tr>';
                                    $table .= '<td>' . $attr . '</td>';

                                    if (!is_array($field['to'])) {
                                        if ($attr === 'service_opts') {
                                            // json
                                            $table .= '<td class="text-right" style="white-space: nowrap;">';
                                            $json = json_decode($field['from'], true);
                                            if (count($json)) {
                                                foreach ($json as $k => $v) {
                                                    $table .= $k . '=' . $v . '<br/>';
                                                }
                                            }
                                            $table .= '</td>';
                                            $table .= '<td style="width: 1%;">=></td>';
                                            $table .= '<td style="white-space: nowrap;">';

                                            $json = json_decode($field['to'], true);
                                            if (count($json)) {
                                                foreach ($json as $k => $v) {
                                                    $table .= $k . '=' . $v . '<br/>';
                                                }
                                            }
                                            $table .= '</td>';
                                        } else {
                                            // text
                                            $table .=
                                                '<td class="text-right" style="white-space: nowrap;">' .
                                                $field['from'] .
                                                '</td>' .
                                                '<td style="width: 1%;">=></td>' .
                                                '<td style="white-space: nowrap;">' .
                                                $field['to'] .
                                                '</td>';
                                        }
                                    }
                                    $table .= '</tr>';
                                }
                                $table .= '</table>';
                            }

                            if (array_key_exists('roles', $event)) {
                                $table .= 'Roles:<table class="table table-condensed" style="width: 1%;">';
                                foreach ($event['roles'] as $role) {
                                    $table .= '<tr>';
                                    $table .= '<td>' . $role . '</td>';
                                    $table .= '</tr>';
                                }
                                $table .= '</table>';
                            }

                            return $table;
                        },
                    ],
                ],
            ]);
            ?>
        </div>
    </div>
</div>
