<?php
/**
 * @var yii\web\View           $this
 * @var array                  $models
 * @var array                  $opts
 * @var yii\widgets\ActiveForm $form
 */

# QR Tech
$model = $models['model_provider'];

echo $form->field($model, 'sms_on_content')
    ->textInput(['maxlength' => true]);
