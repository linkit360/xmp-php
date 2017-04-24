<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View                          $this
 * @var \frontend\models\Campaigns\CreateForm $model
 * @var yii\widgets\ActiveForm                $form
 */
?>
<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-content">
            <?php
            $form = ActiveForm::begin();
            echo $form->field($model, 'title')->textInput(['maxlength' => true]);
            echo $form->field($model, 'description')->textarea();
            echo $form->field($model, 'link')->textInput(['maxlength' => true]);
            echo $form->field($model, 'id_operator')->dropDownList($model->getOperators());
            echo $form->field($model, 'id_service')->dropDownList(
                [
                    '6188e39d-373e-4015-885d-f55e0fdbf030' => 'Test',
                ]
            );

            echo $form->field($model, 'id_lp')->dropDownList(
                [null => 'Please Select',] + $model->getLps()
            );

            echo $form->field($model, 'status')->radioList(
                [
                    1 => 'Active',
                    0 => 'Inactive',
                ],
                [
                    'separator' => '<br/>',
                ]
            );

            echo Html::submitButton(
                'Create',
                [
                    'class' => 'btn btn-success',
                ]
            );

            ActiveForm::end();
            ?>
        </div>
    </div>
</div>
