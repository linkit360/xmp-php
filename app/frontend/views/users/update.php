<?php

/**
 * @var yii\web\View                     $this
 * @var \frontend\models\Users\UsersForm $model
 */

$this->title = 'Update User: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
echo $this->render(
    '_form',
    [
        'model' => $model,
    ]
);
