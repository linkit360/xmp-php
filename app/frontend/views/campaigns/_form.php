<?php
use yii\helpers\Html;
use yii\jui\SliderInput;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View                             $this
 * @var \frontend\models\Campaigns\CampaignsForm $model
 * @var yii\widgets\ActiveForm                   $form
 */

$this->params['subtitle'] = 'URL and LP manager for services';
$form = ActiveForm::begin();
?>
<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-content">
            <?php
            echo $form->field($model, 'title')->textInput(['maxlength' => true]);
            echo $form->field($model, 'description')->textarea();
            echo $form->field($model, 'link')->textInput(['maxlength' => true]);
            echo $form->field($model, 'id_operator')->dropDownList($model->getOperators());
            echo $form->field($model, 'id_service')->dropDownList($model->getServices());
            echo $form->field($model, 'id_lp')->dropDownList($model->getLps());
            echo $form->field($model, 'autoclick_enabled')->checkbox();
            echo $form->field($model, 'autoclick_ratio')->widget(
                SliderInput::classname(),
                [
                    'clientOptions' => [
                        'min' => 1,
                        'max' => 10,
                        'step' => 1,
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

                echo "&nbsp;";
                echo Html::submitButton(
                    'Create',
                    [
                        'class' => 'btn btn-success',
                    ]
                );
                ?>
            </div>
        </div>
    </div>
</div>
<?php
ActiveForm::end();
?>
