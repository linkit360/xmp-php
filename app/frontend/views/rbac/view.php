<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View             $this
 * @var common\models\RBAC\Items $model
 * @var array                    $perms
 */

$this->title = 'Role: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'RBAC', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hpanel col-lg-6">
    <div class="panel-body">
        <p>
            <?= Html::a('Update', ['update', 'id' => $model->name], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->name], [
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
                'name',
//                'type',
                'description:ntext',
//                'rule_name',
//                'data',
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]);
        ?>
    </div>
</div>

<div class="hpanel col-lg-6">
    <div class="panel-body">
        <h1>
            Permissions
        </h1>

        <table class="table table-condensed">
            <?php
            /** @var \yii\rbac\Permission $perm */
            foreach ($perms as $perm) {
                ?>
                <tr>
                    <td>
                        <?= Html::encode($perm->name) ?>
                    </td>

                    <td>
                        <?= nl2br(Html::encode($perm->description)) ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
</div>
