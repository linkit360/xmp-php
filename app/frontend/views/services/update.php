<?php
/**
 * @var yii\web\View $this
 * @var array        $models
 * @var array        $opts
 */

$model = $models['model_service'];

$this->title = 'Update Services: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Services', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
echo $this->render(
    '_form',
    [
        'models' => $models,
        'stepNow' => 3,
        'opts' => $opts,
    ]
);
