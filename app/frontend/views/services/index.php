<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View                $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var array                       $providers
 */

$this->title = 'Services';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-content">
            <p>
                <?= Html::a('Create Service', ['create'], ['class' => 'btn btn-success']) ?>
            </p>
            <?php
            echo GridView::widget(
                [
                    'dataProvider' => $dataProvider,
                    'columns' => [
//                        ['class' => 'yii\grid\SerialColumn'],
//                        'id',
                        'title',
//                        'description',
                        [
                            'attribute' => 'id_provider',
                            'headerOptions' => [
                                'width' => '140',
                                'class' => 'text-right',
                            ],
                            'contentOptions' => function () {
                                return ['class' => 'text-right'];
                            },
                            'content' => function ($data) use ($providers) {
                                return $providers[$data['id_provider']]['name'];
                            },
                        ],
//                        'id_user',
                        // 'status',
                        'created_at',
                        'updated_at',
                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]
            );
            ?>
        </div>
    </div>
</div>
