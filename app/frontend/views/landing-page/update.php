<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Lps */

$this->title = 'Update Lp: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Lps', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lp-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
