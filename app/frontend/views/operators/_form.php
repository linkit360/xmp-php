<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View            $this
 * @var common\models\Operators $model
 * @var yii\widgets\ActiveForm  $form
 * @var string                  $title
 */

$this->params['subtitle'] = 'Operators management';
?>
<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-content">
            <?php
            $form = ActiveForm::begin();
            echo $form->field($model, 'name')->textInput(['maxlength' => true]);
            echo $form->field($model, 'id_provider')->dropDownList($model->getProviders());
            echo $form->field($model, 'isp')->textInput(['maxlength' => true]);
            echo $form->field($model, 'msisdn_prefix')->textInput(['maxlength' => true]);
            echo $form->field($model, 'mcc')->textInput(['maxlength' => true]);
            echo $form->field($model, 'mnc')->textInput(['maxlength' => true]);
            echo $form->field($model, 'code')->textInput();
            echo Html::submitButton(
                $model->isNewRecord ? 'Create' : 'Update',
                [
                    'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
                ]
            );
            ActiveForm::end();
            ?>
        </div>
    </div>
</div>
