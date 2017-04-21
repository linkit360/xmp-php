<?php
/**
 * @var yii\web\View        $this
 * @var common\models\Users $model
 */

$this->title = 'Create User';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
echo $this->render(
    '_form',
    [
        'model' => $model,
    ]
);
