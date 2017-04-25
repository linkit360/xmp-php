<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View                             $this
 * @var \frontend\models\Campaigns\CampaignsForm $model
 * @var yii\widgets\ActiveForm                   $form
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
            echo $form->field($model, 'id_service')->dropDownList($model->getServices());
            echo $form->field($model, 'id_lp')->dropDownList($model->getLps());
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
