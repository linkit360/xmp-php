<?php
use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View                $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var \common\models\Providers    $search
 */
$this->title = 'Providers';
$this->params['subtitle'] = 'Providers management';
$this->params['breadcrumbs'][] = $this->title;

$countries = \common\models\Countries::find()
    ->indexBy('id')
    ->asArray()
    ->all()
?>
<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-content">
            <p>
                <?php
                echo Html::a(
                    'Create Provider',
                    ['create'],
                    ['class' => 'btn btn-success']
                );
                ?>
            </p>

            <?php
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $search,
                'columns' => [
//                    'id',
                    'name',
                    'name_alias',
                    [
                        'attribute' => 'id_country',
                        'filter' => false,
                        'content' => function ($row) use ($countries) {
                            return $countries[$row['id_country']]['name'];
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
