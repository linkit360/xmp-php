<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Instances */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Instances', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-lg-12">
    <div class="ibox">
        <div class="ibox-content">
            <p>
                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) ?>
            </p>

            <?php
            echo DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'id_operator',
                    'status',
                ],
            ]);
            ?>
        </div>
    </div>
</div>
