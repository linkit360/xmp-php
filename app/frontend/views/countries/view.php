<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View            $this
 * @var common\models\Countries $model
 */
$this->title = $model->name;
$this->params['subtitle'] = 'Countries management';
$this->params['breadcrumbs'][] = ['label' => 'Countries', 'url' => ['index']];
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
                    'name',
                    'code',
                    'status',
                    'iso',
                    'priority',
                ],
            ]);
            ?>
        </div>
    </div>
</div>
