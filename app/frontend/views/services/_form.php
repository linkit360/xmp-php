<?php
/**
 * @var yii\web\View           $this
 * @var array                  $models
 * @var array                  $opts
 * @var yii\widgets\ActiveForm $form
 * @var integer                $stepNow
 */
$this->params['subtitle'] = 'Billing setting and content';

echo $this->render(
    '_steps',
    [
        'stepNow' => $stepNow,
    ]
);

echo $this->render(
    '_step_' . $stepNow,
    [
        'models' => $models,
        'opts' => $opts,
    ]
);
