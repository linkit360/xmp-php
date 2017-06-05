<?php

use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View           $this
 * @var array                  $models
 * @var array                  $opts
 * @var yii\widgets\ActiveForm $form
 */

$this->params['subtitle'] = 'Service Info';

/** @var \frontend\models\Services\ServicesForm $model */
$model = $models['model_service'];
$content = $model->getContentForm($opts['country']->id);
$form = ActiveForm::begin();
?>
<div class="col-lg-6">
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

            echo $form->field($model, 'price')
                ->textInput(['maxlength' => true])
                ->hint($opts['country']->currency != '' ? 'Currency: ' . $opts['country']->currency : '');

            echo $form->field($model, 'id_service')->textInput(['maxlength' => true]);
            echo $form->field($model, 'id_provider')->hiddenInput()->label(false);

            //            if ($model->id_content) {
            //                $cont = json_decode($model->id_content, true);
            //                dump($cont);
            //            }

            echo $form->field($model, 'content')->widget(
                Select2::classname(),
                [
                    'data' => $content,
                    'options' => [
                        'placeholder' => 'Select content ...',
                        'multiple' => true,
                    ],
                    'pluginOptions' => [
                        'escapeMarkup' => new JsExpression("function(m) { return m; }"),
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

<div class="col-lg-6">
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
