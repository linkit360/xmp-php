<?php
/**
 * @var yii\web\View            $this
 * @var common\models\Providers $model
 */
$this->title = 'Update Provider: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Providers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
echo $this->render('_form', [
    'model' => $model,
    'title' => $this->title,
]);
