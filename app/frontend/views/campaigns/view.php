<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View            $this
 * @var common\models\Campaigns $model
 */

$this->title = 'Campaign: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Campaigns', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-content">
            <p>
                <?php
                echo Html::a(
                    'Update',
                    ['update', 'id' => $model->id],
                    ['class' => 'btn btn-primary']
                );

                echo '&nbsp;';
                echo Html::a(
                    'Delete',
                    ['delete', 'id' => $model->id],
                    [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]
                );
                ?>
            </p>

            <?php
            echo DetailView::widget([
                'model' => $model,
                'attributes' => [
//                    'id',
//                    'id_user',
                    'title',
                    'description',
                    'link',
                    'id_service',
                    'status',
                    'created_at',
                    'updated_at',
                ],
            ]);
            ?>
        </div>
    </div>
</div>
