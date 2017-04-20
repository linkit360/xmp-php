<?php
/**
 * @var yii\web\View            $this
 * @var common\models\Providers $model
 */
$this->title = 'Create Provider';
$this->params['breadcrumbs'][] = ['label' => 'Providers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
echo $this->render('_form', [
    'model' => $model,
    'title' => $this->title,
]);
