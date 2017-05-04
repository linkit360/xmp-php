<?php

use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View                $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'Landing Pages';
$this->params['subtitle'] = 'LP manager and designer';
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
                <?= Html::a('Create Lp', ['designer'], ['class' => 'btn btn-success']) ?>
            </p>

            <?php
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'class' => yii\grid\SerialColumn::class,
                        'contentOptions' => [
                            'style' => 'width: 1%; white-space: nowrap;',
                        ],
                    ],
//                    'id',
//                'id_user',
                    'title',
//                'status',
                    [
                        'attribute' => 'created_at',
                        'filter' => false,
                        'contentOptions' => [
                            'style' => 'width: 1%; white-space: nowrap;',
                        ],
                        'content' => function ($row) {
                            return date('Y-m-d H:i:s', strtotime($row['created_at']));
                        },
                    ],
//                    'updated_at',
//                    ['class' => yii\grid\ActionColumn::class],

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
