<?php

use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View                     $this
 * @var common\models\Content\Categories $model
 * @var yii\widgets\ActiveForm           $form
 */

$ic = new \common\helpers\IconsHelper();
$icons = [];
foreach ($ic->getAll() as $icon) {
    $icons[$icon] = '<i class="fa ' . $icon . '" aria-hidden="true"></i> ' . $icon;
}
?>
<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-content">
            <?php
            $form = ActiveForm::begin();
            echo $form->field($model, 'title')->textInput(['maxlength' => true]);
            echo $form->field($model, 'icon')->widget(
                Select2::classname(),
                [
                    'data' => $icons,
                    'options' => [
                        'placeholder' => 'Select icon ...',
                    ],
                    'pluginOptions' => [
//                        'allowClear' => true,
                        'escapeMarkup' => new JsExpression("function(m) { return m; }"),
                    ],
                ]
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
