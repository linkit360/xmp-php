<?php
/**
 * @var yii\web\View             $this
 * @var common\models\RBAC\Items $model
 */

$this->title = 'Update Role: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'RBAC', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = 'Update';
echo $this->render(
    '_form',
    [
        'model' => $model,
    ]
);
