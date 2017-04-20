<?php
/**
 * @var $this  yii\web\View
 * @var $model common\models\RBAC\Items
 */
$this->title = 'Create Role';
$this->params['breadcrumbs'][] = ['label' => 'RBAC', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
echo $this->render('_form', [
    'model' => $model,
]);
