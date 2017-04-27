<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View                  $this
 * @var common\models\MsisdnBlacklist $model
 */

$this->title = $model->id;
$this->params['subtitle'] = 'MSISDN charging blaclist';
$this->params['breadcrumbs'][] = ['label' => 'Msisdn Blacklist', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content animate-panel">
    <div class="row">
        <div class="hpanel">
            <div class="panel-body">
                <h1>
                    <?= Html::encode($this->title) ?>
                </h1>

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
                    ?>
                </p>

                <?php
                echo DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'msisdn',
                        'provider_name',
                        'operator_code',
                        'created_at',
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
