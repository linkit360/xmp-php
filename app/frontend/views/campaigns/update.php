<?php
/**
 * @var yii\web\View                            $this
 * @var frontend\models\Campaigns\CampaignsForm $model
 */

$this->title = 'Update Campaign: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Campaigns', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
echo $this->render(
    '_form',
    [
        'model' => $model,
    ]
);
