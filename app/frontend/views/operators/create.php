<?php
/**
 * @var yii\web\View            $this
 * @var common\models\Operators $model
 */

$this->title = 'Create Operator';
$this->params['breadcrumbs'][] = ['label' => 'Operators', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
echo $this->render('_form', [
    'model' => $model,
    'title' => $this->title,
]);
