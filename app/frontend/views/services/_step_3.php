<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\widgets\Select2;
use kartik\money\MaskMoney;

/**
 * @var yii\web\View           $this
 * @var array                  $models
 * @var array                  $opts
 * @var yii\widgets\ActiveForm $form
 */

$this->params['subtitle'] = 'Service Info';

/** @var \frontend\models\Services\ServicesForm $model */
$model = $models['model_service'];
$form = ActiveForm::begin();
?>
<div class="col-lg-4">
    <div class="ibox">
        <div class="ibox-title">
            <h5>
                Service Info
            </h5>
        </div>

        <div class="ibox-content">
            <p>
                <?php
                echo Html::a(
                    'Back',
                    ['index'],
                    [
                        'class' => 'btn btn-default',
                    ]
                );
                ?>
            </p>

            <?php
            echo $form->field($model, 'title')->textInput(['maxlength' => true]);
            echo $form->field($model, 'description')->textarea();

            if (!$model->price_raw && $model->price) {
                // crud update
                $model->price_raw = $model->price / 100;
            }
            echo $form->field($model, 'price_raw')
                ->widget(
                    MaskMoney::className(),
                    [
                        'pluginOptions' => [
                            'prefix' => html_entity_decode($opts['country']->currency . " "),
                            'precision' => 2,
                        ],
                    ]
                );

            echo $form->field($model, 'id_service')->textInput(['maxlength' => true]);
            echo $form->field($model, 'id_provider')->hiddenInput()->label(false);

            $model->content = [];
            if ($model->id_content) {
                $model->content = json_decode($model->id_content, true);
            }

            echo $form->field($model, 'content')
                ->widget(
                    Select2::classname(),
                    [
                        'data' => $model->getContentForm($opts['country']->id),
                        'options' => [
                            'placeholder' => 'Select content',
                            'multiple' => true,
                        ],
                    ]
                );
            ?>
            <div class="text-right">
                <?php
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

<div class="col-lg-4">
    <div class="ibox">
        <div class="ibox-title">
            <h5>
                Provider Options
            </h5>
        </div>

        <div class="ibox-content">
            <?php
            echo $this->render(
                'providers/' . $model->id_provider,
                [
                    'models' => $models,
                    'form' => $form,
                    'opts' => $opts,
                ]
            );
            ?>
        </div>
    </div>
</div>
<?php
ActiveForm::end();
?>
