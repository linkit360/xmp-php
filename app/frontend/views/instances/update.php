<?php
/**
 * @var yii\web\View            $this
 * @var common\models\Instances $model
 */

$this->title = 'Update Instance: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Instances', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

echo $this->render(
    '_form',
    [
        'model' => $model,
    ]
);
