<?php
/**
 * @var yii\web\View           $this
 * @var array                  $models
 * @var array                  $opts
 * @var yii\widgets\ActiveForm $form
 * @var yii\widgets\ActiveForm $country
 */

/*
    QR Tech TH service settings:

    SMS On Content // struct.SMSOnContent // Текст СМС который отправляется при отправке контента.
    (FYI: может содержать URL и текст на разных языках)
 */

$model = $models['model_provider'];
echo $form->field($model, 'sms_on_content')
    ->textInput(['maxlength' => true]);
