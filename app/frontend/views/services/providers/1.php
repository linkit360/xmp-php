<?php
/**
 * @var yii\web\View           $this
 * @var array                  $models
 * @var array                  $opts
 * @var yii\widgets\ActiveForm $form
 * @var yii\widgets\ActiveForm $country
 */

# Cheese
/*
    Cheese Mobile service settings:

    Price per transaction (Currency should be visible by default based on selected country)
    Dropdown with multi select of content. (fill with dummy row)
 */

$model = $models['model_provider'];
echo $form->field($model, 'price')
    ->textInput(['maxlength' => true])
    ->hint($opts['country']->currency != '' ? 'Currency: ' . $opts['country']->currency : '');
