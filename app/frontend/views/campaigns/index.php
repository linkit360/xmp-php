<?php
use yii\grid\SerialColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;

/**
 * @var yii\web\View                $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var array                       $data
 */

$this->title = 'Campaigns';
$this->params['subtitle'] = 'URL and LP manager for services';
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

            echo $form->field($model, 'id_operator')->widget(
                Select2::classname(),
                [
                    'data' => $data['id_operator'],
                    'options' => [
                        'placeholder' => 'Operator',
                    ],
                ]
            );

            echo $form->field($model, 'title');
            //echo $form->field($model, 'description');
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
                        'class' => SerialColumn::class,
                        'contentOptions' => [
                            'style' => 'width: 1%; white-space: nowrap;',
                        ],
                    ],
                    'title',
                    [
                        'attribute' => 'id_operator',
                        'content' => function ($row) use ($data) {
                            return $data['id_operator'][$row['id_operator']];
                        },
                    ],
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
