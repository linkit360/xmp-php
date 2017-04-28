<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View                $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;

$helper = new \common\helpers\ModalHelper();
$helper->modalDelete($this);
?>
<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-content">
            <p>
                <?php
                echo Html::a(
                    'Create Category',
                    ['create'],
                    ['class' => 'btn btn-success']
                );
                ?>
            </p>

            <?php
            echo GridView::widget(
                [
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        [
                            'attribute' => 'name',
                            'filter' => false,
                            'content' => function ($row) {
                                return '<i class="fa ' . $row['icon'] . '" aria-hidden="true"></i>&nbsp;' . $row['title'];
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
                ]
            );
            ?>
        </div>
    </div>
</div>
