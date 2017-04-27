<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View           $this
 * @var \yii\base\Model        $modelProvider
 * @var common\models\Services $model
 */

$this->title = 'Service: ' . $model->title;
$this->params['subtitle'] = 'Billing setting and content';
$this->params['breadcrumbs'][] = ['label' => 'Services', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-title">
            <h5>
                <?php
                echo Html::a(
                        'Update',
                        ['update', 'id' => $model->id],
                        ['class' => 'btn btn-xs btn-primary']
                    ) . '&nbsp;';

                echo Html::a(
                    'Delete',
                    ['delete', 'id' => $model->id],
                    [
                        'class' => 'btn btn-xs btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]
                );
                ?>
            </h5>
        </div>

        <div class="ibox-content">
            <?php
            echo DetailView::widget(
                [
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'title',
                        'description',
                        'id_provider',
                        'id_user',
                        'status',
//                        'created_at',
//                        'updated_at',
                    ],
                ]
            );
            ?>
        </div>
    </div>
</div>

<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-title">
            <h5>
                Provider Options
            </h5>
        </div>

        <div class="ibox-content">
            <table class="table">
                <?php
                foreach ($modelProvider->attributes as $opt => $val) {
                    ?>
                    <tr>
                        <td style="width: 120px;">
                            <?= $modelProvider->attributeLabels()[$opt] ?>
                        </td>

                        <td>
                            <?= $val ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
    </div>
</div>
