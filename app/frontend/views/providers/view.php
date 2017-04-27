<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View            $this
 * @var common\models\Providers $model
 */

$this->title = $model->name;
$this->params['subtitle'] = 'Providers management';
$this->params['breadcrumbs'][] = ['label' => 'Providers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hpanel col-lg-6">
    <div class="panel-body">
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
                'id_country',
            ],
        ]);
        ?>
    </div>
</div>
