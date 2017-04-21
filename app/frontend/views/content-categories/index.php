<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View                $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;
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
                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]
            );
            ?>
        </div>
    </div>
</div>
