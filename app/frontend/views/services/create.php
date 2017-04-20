<?php
/**
 * @var yii\web\View $this
 * @var array        $models
 * @var array        $opts
 * @var integer      $stepNow
 */

$this->title = 'Create Service';
$this->params['breadcrumbs'][] = ['label' => 'Services', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
echo $this->render(
    '_form',
    [
        'models' => $models,
        'opts' => $opts,
        'stepNow' => $stepNow,
    ]
);
