<?php
/**
 * @var yii\web\View            $this
 * @var common\models\Campaigns $model
 */

$this->title = 'Create Campaign';
$this->params['breadcrumbs'][] = ['label' => 'Campaigns', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
echo $this->render('_form', [
    'model' => $model,
]);
