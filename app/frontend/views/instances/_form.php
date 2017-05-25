<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View                         $this
 * @var \frontend\models\Instances\Instances $model
 * @var yii\widgets\ActiveForm               $form
 */
?>
<div class="col-lg-12">
    <div class="ibox">
        <div class="ibox-content">
            <?php
            $form = ActiveForm::begin();
            //            echo $form->field($model, 'id')->textInput();
            //            echo $form->field($model, 'id_operator')->textInput();
            echo $form->field($model, 'id_provider')->dropDownList($model->getProviders());
            //            echo $form->field($model, 'status')->textInput();

            echo Html::submitButton(
                $model->isNewRecord ? 'Create' : 'Update',
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
            );

            ActiveForm::end();
            ?>
        </div>
    </div>
</div>
