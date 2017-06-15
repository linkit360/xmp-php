<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

use kartik\daterange\DateRangePicker;
use kartik\widgets\Select2;

/**
 * @var yii\web\View                $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var array                       $users
 */

$this->title = 'Landing Pages';
$this->params['subtitle'] = 'LP manager and designer';
$this->params['breadcrumbs'][] = $this->title;

$helper = new \common\helpers\ModalHelper();
$helper->modalDelete($this);

$form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
]);
?>
<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-content">
            <?php
            echo $form->field($model, 'title');
            //echo $form->field($model, 'description');

            if (count($users)) {
                echo $form->field($model, 'id_user')->widget(
                    Select2::classname(),
                    [
                        'data' => $users,
                        'options' => [
                            'placeholder' => 'User',
                        ],
                    ]
                );
            }

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
        </div>
    </div>
</div>

<?php
ActiveForm::end();
echo '</div><div class="row">';
?>

<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-content">
            <p>
                <?= Html::a('Create Lp', ['designer'], ['class' => 'btn btn-success']) ?>
            </p>

            <?php
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'rowOptions' => function ($model) {
                    return [
                        "class" => $model->status ?: "danger",
                    ];
                },
                'columns' => [
                    [
                        'class' => yii\grid\SerialColumn::class,
                        'contentOptions' => [
                            'style' => 'width: 1%; white-space: nowrap;',
                        ],
                    ],
                    'title',
                    [
                        'attribute' => 'id_user',
                        'visible' => count($users),
                        'filter' => false,
                        'headerOptions' => [
                            'style' => 'width: 1%; white-space: nowrap;',
                        ],
                        'contentOptions' => [
                            'style' => 'width: 1%; white-space: nowrap;',
                        ],
                        'content' => function ($row) use ($users) {
                            return Html::a(
                                $users[$row['id_user']],
                                '/users/' . $row['id_user'],
                                [
                                    'target' => '_blank',
                                ]
                            );
                        },
                    ],
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

                            if (!$row->status) {
                                return $html;
                            }

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
