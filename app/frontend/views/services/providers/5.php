<?php
/**
 * @var yii\web\View           $this
 * @var array                  $models
 * @var array                  $opts
 * @var yii\widgets\ActiveForm $form
 */

# Mobilink
use kartik\widgets\Select2;

$model = $models['model_provider'];

echo $form->field($model, 'sms_on_content')
    ->textInput(['maxlength' => true]);

echo $form->field($model, 'sms_on_subscribe')
    ->textInput(['maxlength' => true]);

echo $form->field($model, 'sms_on_unsubscribe')
    ->textInput(['maxlength' => true]);

echo $form->field($model, 'retry_days');
echo $form->field($model, 'inactive_days');
echo $form->field($model, 'grace_days');
echo $form->field($model, 'minimal_touch_times');

$model->periodic_days = [
    0,
    1,
    2,
    3,
    4,
    5,
    6,
];

echo $form->field($model, 'periodic_days')
    ->widget(Select2::classname(), [
        'data' => [
            0 => "Monday",
            1 => "Tuesday",
            2 => "Wednesday",
            3 => "Thursday",
            4 => "Friday",
            5 => "Saturday",
            6 => "Sunday",
        ],
        'showToggleAll' => true,
        'hideSearch' => true,
        'maintainOrder' => true,
        'options' => [
            'placeholder' => 'Select Days',
            'multiple' => true,
        ],
        'pluginOptions' => [
            'tags' => true,
            'maximumInputLength' => 7,
        ],
    ]);
