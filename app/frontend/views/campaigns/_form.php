<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View                             $this
 * @var \frontend\models\Campaigns\CampaignsForm $model
 * @var yii\widgets\ActiveForm                   $form
 */

$this->params['subtitle'] = 'URL and LP manager for services';
$form = ActiveForm::begin();
?>
<div class="col-lg-5">
    <div class="ibox">
        <div class="ibox-content">
            <?php
            echo $form->field($model, 'id_old')->textInput(['maxlength' => true]);
            echo $form->field($model, 'title')->textInput(['maxlength' => true]);
            echo $form->field($model, 'description')->textarea();
            echo $form->field($model, 'link')->textInput(['maxlength' => true]);
            echo $form->field($model, 'id_service')->dropDownList($model->getServices());
            echo $form->field($model, 'id_lp')->dropDownList($model->getLps());
            echo $form->field($model, 'autoclick_enabled')->checkbox();
            echo $form->field($model, 'autoclick_ratio')->dropDownList([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
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
<?php
ActiveForm::end();
?>
