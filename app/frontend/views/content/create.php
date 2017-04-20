<?php

/**
 * @var yii\web\View                  $this
 * @var common\models\Content\Content $model
 */

$this->title = 'Add Content';
$this->params['breadcrumbs'][] = ['label' => 'Content', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
echo $this->render(
    '_form',
    [
        'model' => $model,
    ]
);
