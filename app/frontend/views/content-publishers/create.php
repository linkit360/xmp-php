<?php
/**
 * @var yii\web\View                     $this
 * @var common\models\Content\Publishers $model
 */

$this->title = 'Create Publisher';
$this->params['breadcrumbs'][] = ['label' => 'Publishers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
echo $this->render(
    '_form',
    [
        'model' => $model,
    ]
);
