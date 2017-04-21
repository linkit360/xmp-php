<?php
/**
 * @var yii\web\View                             $this
 * @var yii\bootstrap\ActiveForm                 $form
 * @var \frontend\models\Users\ResetPasswordForm $model
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-content">
            <p>Please choose your new password:</p>

            <?php
            $form = ActiveForm::begin(['id' => 'reset-password-form']);
            echo $form->field($model, 'password')->passwordInput(['autofocus' => true]);
            echo Html::submitButton('Save', ['class' => 'btn btn-primary']);
            ActiveForm::end();
            ?>
        </div>
    </div>
</div>
