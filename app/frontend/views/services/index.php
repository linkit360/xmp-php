<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use kartik\daterange\DateRangePicker;
use kartik\widgets\Select2;

/**
 * @var yii\web\View                $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var array                       $providers
 * @var array                       $countries
 */

$this->title = 'Services';
$this->params['subtitle'] = 'Billing setting and content';
$this->params['breadcrumbs'][] = $this->title;

$helper = new \common\helpers\ModalHelper();
$helper->modalDelete($this);
?>
<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-content">
            <?php
            $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
            ]);
            echo $form->field($model, 'title');
            //echo $form->field($model, 'description');
            echo $form->field($model, 'id_country')->widget(
                Select2::classname(),
                [
                    'data' => ArrayHelper::map($countries, 'id', 'name'),
                    'options' => [
                        'placeholder' => 'Country',
                    ],
                ]
            );

            echo $form->field($model, 'id_provider')->widget(
                Select2::classname(),
                [
                    'data' => ArrayHelper::map($providers, 'id', 'name'),
                    'options' => [
                        'placeholder' => 'Provider',
                    ],
                ]
            );
            // echo $form->field($model, 'id_service')
            // echo $form->field($model, 'id_content')
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
            ?>

            <div class="text-right">
                <?php
                echo Html::a(
                    'Reset',
                    ['index'],
                    ['class' => 'btn btn-default']
                );
                echo '&nbsp;';
                echo Html::submitButton(
                    'Search',
                    ['class' => 'btn btn-primary']
                );
                ?>
            </div>

            <?php
            ActiveForm::end();
            ?>
        </div>
    </div>
</div>

<?= '</div><div class="row">' ?>

<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-content">
            <p>
                <?= Html::a('Create Service', ['create'], ['class' => 'btn btn-success']) ?>
            </p>
            <?php
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'class' => \yii\grid\SerialColumn::class,
                    ],
                    'title',
                    [
                        'label' => 'Country',
                        'contentOptions' => [
                            'style' => 'width: 1%; white-space: nowrap;',
                        ],
                        'content' => function ($row) use ($countries, $providers) {
                            return $countries[$providers[$row['id_provider']]['id_country']]['name'];
                        },
                    ],
                    [
                        'attribute' => 'id_provider',
                        'contentOptions' => [
                            'style' => 'width: 1%; white-space: nowrap;',
                        ],
                        'content' => function ($data) use ($providers) {
                            return $providers[$data['id_provider']]['name'];
                        },
                    ],
                    [
                        'attribute' => 'time_create',
                        'contentOptions' => [
                            'style' => 'width: 1%; white-space: nowrap;',
                        ],
                        'content' => function ($row) {
                            return date('Y-m-d H:i:s', strtotime($row['time_create']));
                        },
                    ],
                    [
                        'contentOptions' => [
                            'style' => 'width: 1%; white-space: nowrap;',
                        ],
                        'content' => function ($row) {
                            $html = Html::a(
                                'View',
                                [
                                    'view',
                                    'id' => $row['id'],
                                ],
                                [
                                    'class' => 'btn btn-xs btn-success',
                                ]
                            );

                            $html .= '&nbsp;';
                            $html .= Html::a(
                                'Update',
                                [
                                    'update',
                                    'id' => $row['id'],
                                ],
                                [
                                    'class' => 'btn btn-xs btn-primary',
                                ]
                            );

                            $html .= '&nbsp;';
                            $html .= Html::button(
                                'Delete',
                                [
                                    'class' => 'btn btn-xs btn-danger',
                                    'data-toggle' => 'modal',
                                    'data-target' => '#modalDelete',
                                    'data-rowid' => $row['id'],
                                ]
                            );

                            return $html;
                        },
                    ],
                ],
            ]);
            ?>
        </div>
    </div>
</div>
