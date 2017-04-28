<?php

use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View            $this
 * @var common\models\Countries $model
 * @var yii\widgets\ActiveForm  $form
 */
$this->params['subtitle'] = 'Countries management';

$bundle = \common\assets\InspiniaAsset::register($this);
$flags = new \common\helpers\FlagsHelper();

$fl = [];
foreach ($flags->getAll() as $flag) {
    $fl[$flag] = '<img src="' . $bundle->baseUrl . '/img/flags/16/' . $flag . '.png"> ' . $flag;
}
?>
<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-content">
            <?php
            $form = ActiveForm::begin();
            echo $form->field($model, 'name')->textInput(['maxlength' => true]);
            echo $form->field($model, 'code')->textInput();
            echo $form->field($model, 'iso')->textInput(['maxlength' => true]);
            echo $form->field($model, 'priority')->textInput();
            echo $form->field($model, 'flag')->widget(
                Select2::classname(),
                [
                    'data' => $fl,
                    'options' => [
                        'placeholder' => 'Select a flag ...',
                    ],
                    'pluginOptions' => [
                        'escapeMarkup' => new JsExpression("function(m) { return m; }"),
                    ],
                ]
            );

            echo $form->field($model, 'currency')->textInput()
                ->hint(
                    'Text or <a href="https://www.w3schools.com/charsets/ref_utf_currency.asp" target="_blank">code</a> like ' .
                    Html::encode('&#8364;')
                );
            ?>

            <div class="text-right">
                <?php
                echo Html::a(
                    'Back',
                    ['index'],
                    [
                        'class' => 'btn btn-default',
                    ]
                );

                echo Html::submitButton(
                    $model->isNewRecord ? 'Create' : 'Update',
                    [
                        'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
                    ]
                );
                ?>
            </div>

            <?php
            ActiveForm::end();
            ?>
        </div>
    </div>
</div>
