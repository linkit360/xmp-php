<?php
use yii\helpers\Html;

/**
 * @var yii\web\View                  $this
 * @var common\models\Content\Content $model
 */


$this->title = 'Update Content: ' . Html::encode($model->title);
$this->params['breadcrumbs'][] = ['label' => 'Content', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Html::encode($model->title), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
echo $this->render(
    '_form',
    [
        'model' => $model,
    ]
);
