<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View                $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'Countries';
$this->params['subtitle'] = 'Countries management';
$this->params['breadcrumbs'][] = $this->title;

$bundle = \common\assets\InspiniaAsset::register($this);

?>
<div class="col-lg-4">
    <div class="ibox">
        <div class="ibox-content">
            <p>
                <?php
                echo Html::a(
                    'Create Country',
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
                            'content' => function ($row) use ($bundle) {
                                return '<img src="' . $bundle->baseUrl . '/img/flags/32/' . $row['flag'] . '.png">&nbsp;' . $row['name'];
                            },
                        ],

                        'code',
                        'iso',
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'headerOptions' => [
                                'style' => 'width: 66px;',
                            ],
                        ],
                    ],
                ]
            );
            ?>
        </div>
    </div>
</div>
