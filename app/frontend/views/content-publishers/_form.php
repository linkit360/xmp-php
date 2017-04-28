<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View                     $this
 * @var common\models\Content\Publishers $model
 * @var yii\widgets\ActiveForm           $form
 */
?>
<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-content">
            <?php
            $form = ActiveForm::begin();
            echo $form->field($model, 'title')->textInput(['maxlength' => true]);
            echo $form->field($model, 'description')->textInput(['maxlength' => true]);
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
