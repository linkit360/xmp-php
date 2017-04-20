<?php
/**
 * @var yii\web\View            $this
 * @var common\models\Countries $model
 */
$this->title = 'Create Country';
$this->params['breadcrumbs'][] = ['label' => 'Countries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
echo $this->render(
    '_form',
    [
        'model' => $model,
    ]
);
