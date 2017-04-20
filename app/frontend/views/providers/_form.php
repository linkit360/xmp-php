<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View            $this
 * @var common\models\Providers $model
 * @var yii\widgets\ActiveForm  $form
 * @var string                  $title
 */
?>
<div class="hpanel col-lg-6">
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin();
        echo $form->field($model, 'name')->textInput(['maxlength' => true]);
        echo $form->field($model, 'id_country')->dropDownList($model->getCountries());
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
