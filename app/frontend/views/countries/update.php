<?php
/**
 * @var yii\web\View            $this
 * @var common\models\Countries $model
 */
$this->title = 'Update Country: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Countries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
echo $this->render('_form', [
    'model' => $model,
    'title' => $this->title,
]);
