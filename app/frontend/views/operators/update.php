<?php
/**
 * @var yii\web\View            $this
 * @var common\models\Operators $model
 */

$this->title = 'Update Operator: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Operators', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
echo $this->render(
    '_form',
    [
        'model' => $model,
    ]
);
