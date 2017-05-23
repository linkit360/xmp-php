<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View                      $this
 * @var frontend\models\Search\SearchForm $model
 * @var yii\widgets\ActiveForm            $form
 */
?>
<div class="instances-search">
    <?php
    $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]);

    echo $form->field($model, 'id');
    echo $form->field($model, 'id_operator');
    echo $form->field($model, 'status');
    ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?php
        //echo Html::resetButton('Reset', ['class' => 'btn btn-default'])
        ?>
    </div>

    <?php
    ActiveForm::end();
    ?>
</div>
