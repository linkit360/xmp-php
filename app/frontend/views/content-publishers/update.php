<?php

/**
 * @var yii\web\View                     $this
 * @var common\models\Content\Publishers $model
 */

$this->title = 'Update Publishers: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Publishers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
echo $this->render(
    '_form',
    [
        'model' => $model,
    ]
);
