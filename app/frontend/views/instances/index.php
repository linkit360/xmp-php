<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View                      $this
 * @var frontend\models\Search\SearchForm $searchModel
 * @var yii\data\ActiveDataProvider       $dataProvider
 */

$this->title = 'Instances';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-content">
            <?php
            echo $this->render('_search', ['model' => $searchModel]);
            ?>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="ibox">
        <div class="ibox-content">
            <p>
                <?= Html::a('Create Instances', ['create'], ['class' => 'btn btn-success']) ?>
            </p>

            <?php
            echo GridView::widget([
                'dataProvider' => $dataProvider,
//                'filterModel' => $searchModel,
                'columns' => [
//                    ['class' => 'yii\grid\SerialColumn'],

                    'id',
                    'id_operator',
                    'status',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]);
            ?>
        </div>
    </div>
</div>
