<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View                $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'Campaigns';
$this->params['subtitle'] = 'URL and LP manager for services';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-content">
            <?php
            $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
            ]);

            echo $form->field($model, 'id_service');
            echo $form->field($model, 'id_operator');
            echo $form->field($model, 'id_lp');
            echo $form->field($model, 'title');
            echo $form->field($model, 'description');
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
                <?= Html::a('Create Campaign', ['create'], ['class' => 'btn btn-success']) ?>
            </p>

            <?php
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'class' => \yii\grid\SerialColumn::class,
                        'contentOptions' => [
                            'style' => 'width: 1%; white-space: nowrap;',
                        ],
                    ],
                    'title',
                    [
                        'attribute' => 'created_at',
                        'contentOptions' => [
                            'style' => 'width: 1%; white-space: nowrap;',
                        ],
                        'content' => function ($data) {
                            return date('Y-m-d H:i:s', strtotime($data['created_at']));
                        },
                    ],
                    [
                        'attribute' => 'updated_at',
                        'contentOptions' => [
                            'style' => 'width: 1%; white-space: nowrap;',
                        ],
                        'content' => function ($data) {
                            return date('Y-m-d H:i:s', strtotime($data['updated_at']));
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
                            $html .= Html::a(
                                'Delete',
                                [
                                    'delete',
                                    'id' => $row['id'],
                                ],
                                [
                                    'class' => 'btn btn-xs btn-danger',
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
