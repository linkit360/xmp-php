<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View              $this
 * @var \frontend\models\RbacForm $model
 * @var yii\widgets\ActiveForm    $form
 */
?>
<div class="hpanel col-lg-6">
    <div class="panel-body">
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
        echo Html::submitButton(
            $model->isNewRecord ? 'Create' : 'Update',
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
        );
        ActiveForm::end();
        ?>
    </div>
</div>
