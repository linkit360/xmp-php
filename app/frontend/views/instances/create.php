<?php

/**
 * @var yii\web\View            $this
 * @var common\models\Instances $model
 */

$this->title = 'Create Instance';
$this->params['breadcrumbs'][] = ['label' => 'Instances', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

echo $this->render(
    '_form',
    [
        'model' => $model,
    ]
);
