<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View                     $this
 * @var common\models\Content\Publishers $model
 * @var yii\widgets\ActiveForm           $form
 */

$form = ActiveForm::begin();
?>
<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-content">
            <?php
            echo $form->field($model, 'title')->textInput(['maxlength' => true]);
            echo $form->field($model, 'description')->textarea();
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

                echo "&nbsp;";
                echo Html::submitButton(
                    $model->isNewRecord ? 'Create' : 'Update',
                    [
                        'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
                    ]
                );
                ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>
