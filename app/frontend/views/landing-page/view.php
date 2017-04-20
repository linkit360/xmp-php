<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View      $this
 * @var common\models\Lps $model
 */

$this->title = 'Landing Page';
$this->params['subtitle'] = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Landing Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-lg-12">
    <div class="ibox">
        <div class="ibox-content">
            <p>
                <?php
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

                echo '&nbsp;';
                echo Html::a(
                    'Download',
                    ['download', 'id' => $model->id],
                    ['class' => 'btn btn-primary']
                );
                ?>
            </p>

            <?php
            echo DetailView::widget(
                [
                    'model' => $model,
                    'attributes' => [
//                        'id',
//                        'id_user',
                        'title',
                        'description',
                        'status',
                        'created_at',
                        'updated_at',
                    ],
                ]
            );
            ?>
        </div>
    </div>
</div>
