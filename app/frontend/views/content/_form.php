<?php

use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

use kartik\widgets\FileInput;
use kartik\widgets\Select2;

/**
 * @var yii\web\View                $this
 * @var frontend\models\ContentForm $model
 * @var yii\widgets\ActiveForm      $form
 */

$this->params['subtitle'] = 'Service content';
?>
<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-content">
            <?php
            $form = ActiveForm::begin(
                [
                    'options' =>
                        [
                            'enctype' => 'multipart/form-data',
                        ],
                ]
            );

            echo $form->field($model, 'title')->textInput(['maxlength' => true]);
            echo $form->field($model, 'id_category')
                ->dropDownList(
                    [null => 'Please Select'] + $model->getCategories()
                );

            echo $form->field($model, 'id_publisher')
                ->dropDownList(
                    [null => 'Please Select'] + $model->getPublishers()
                );

            echo $form->field($model, 'blacklist_tmp')->widget(
                Select2::classname(),
                [
                    'data' => $model->getCountries(),
                    'options' => [
                        'placeholder' => 'Select ...',
                        'multiple' => true,
                    ],
                    'pluginOptions' => [
                        'escapeMarkup' => new JsExpression("function(m) { return m; }"),
                    ],
                ]
            );

            if ($model->isNewRecord) {
                echo $form->field($model, 'file')
                    ->widget(
                        FileInput::classname(),
                        [
                            'options' => [
                                'multiple' => false,
                            ],
                            'pluginOptions' => [
                                'maxFileCount' => 1,
//                        'uploadUrl' => '/',
                                'showUpload' => false,
                            ],
                        ]
                    )
                    ->hint('You cannot change file later.');
            } else {
                echo Html::a(
                        'Download File',
                        ['download', 'id' => $model->id],
                        ['class' => 'btn btn-primary']
                    ) . '<br/><br/>';
            }

            /*
            echo $form->field($model, 'status')->radioList(
                [
                    1 => 'Active',
                    0 => 'Inactive',
                ],
                [
                    'separator' => '<br/>',
                ]
            );
            */
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
