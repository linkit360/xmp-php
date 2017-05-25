<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View                         $this
 * @var frontend\models\Instances\SearchForm $searchModel
 * @var yii\data\ActiveDataProvider          $dataProvider
 */

$this->title = 'Instances';
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

            echo $form->field($searchModel, 'id');
            echo $form->field($searchModel, 'id_provider');
            echo $form->field($searchModel, 'status');
            ?>

            <div class="form-group">
                <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                <?php
                //echo Html::resetButton('Reset', ['class' => 'btn btn-default'])
                ?>
            </div>

            <?php
            ActiveForm::end();
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
                    'id_provider',
                    'status',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]);
            ?>
        </div>
    </div>
</div>
