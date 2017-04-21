<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View                      $this
 * @var \frontend\models\Users\UpdateForm $model
 */

$this->title = 'Update User: ' . $model->user->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->user->username, 'url' => ['view', 'id' => $model->user->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="col-lg-4">
    <div class="ibox">
        <div class="ibox-content">
            <?php
            $form = ActiveForm::begin();
            echo $form->field($model, 'roles')->checkboxList(
                $model->getRolessAll(),
                [
                    'separator' => '<br/>',
                ]
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

            echo $form->field($model, 'new_pass')->checkbox([
                'label' => 'Ask new password on login',
            ]);

            echo Html::submitButton('Update', ['class' => 'btn btn-success']);
            ActiveForm::end();
            ?>
        </div>
    </div>
</div>
