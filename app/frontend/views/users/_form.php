<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View                     $this
 * @var \frontend\models\Users\UsersForm $model
 */

$this->params['subtitle'] = 'Users and roles management';

$form = ActiveForm::begin();
?>
<div class="col-lg-4">
    <div class="ibox">
        <div class="ibox-content">
            <?php
            echo $form->field($model, 'username')->textInput(['autofocus' => true]);
            echo $form->field($model, 'email');

            echo $form->field($model, 'password')->passwordInput();
            echo $form->field($model, 'status')->radioList(
                [
                    1 => 'Active',
                    0 => 'Inactive',
                ],
                [
                    'separator' => '<br/>',
                ]
            );

            echo $form->field($model, 'new_pass')->checkbox([
                'label' => 'Ask new password on login',
            ]);

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
        </div>
    </div>
</div>

<div class="col-lg-4">
    <div class="ibox">
        <div class="ibox-content">
            <?php
            echo $form->field($model, 'roles')->checkboxList(
                $model->getRolessAll(),
                [
                    'separator' => '<br/>',
                ]
            );
            ?>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>
