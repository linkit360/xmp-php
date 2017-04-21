<?php
/**
 * @var yii\web\View        $this
 * @var common\models\Users $model
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Create User';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-lg-4">
    <div class="ibox">
        <div class="ibox-content">
            <?php
            $form = ActiveForm::begin();
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

            echo Html::submitButton('Create', ['class' => 'btn btn-success']);
            ActiveForm::end();
            ?>
        </div>
    </div>
</div>
