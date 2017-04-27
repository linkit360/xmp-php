<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View                $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'Landing Pages';
$this->params['subtitle'] = 'LP manager and designer';
$this->params['breadcrumbs'][] = $this->title;
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
