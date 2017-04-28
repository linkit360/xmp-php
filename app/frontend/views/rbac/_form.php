<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View              $this
 * @var \frontend\models\RbacForm $model
 * @var yii\widgets\ActiveForm    $form
 */
?>
<div class="col-lg-4">
    <div class="ibox">
        <div class="ibox-content">
            <?php
            $form = ActiveForm::begin();
            echo $form->field($model, 'name')->textInput(['maxlength' => true]);
            echo $form->field($model, 'description')->textarea(['rows' => 6]);
            echo $form->field($model, 'permissions')->checkboxList(
                $model->getPermissionsAll(),
                [
                    'separator' => '<br/>',
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
