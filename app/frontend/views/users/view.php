<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View        $this
 * @var common\models\Users $model
 */

$this->title = 'User: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-lg-6">
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
                    'username',
//                'auth_key',
//                'password_hash',
//                'password_reset_token',
                    'email:email',
                    'status',
                    'created_at',
                    'updated_at',
                ],
            ]);
            ?>
        </div>
    </div>
</div>
