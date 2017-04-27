<?php
use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View                $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var \common\models\Operators    $search
 */

$this->title = 'Operators';
$this->params['subtitle'] = 'Operators management';
$this->params['breadcrumbs'][] = $this->title;

$provs = \common\models\Providers::find()
    ->indexBy('id')
    ->asArray()
    ->all()
?>
<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-content">
            <p>
                <?= Html::a('Create Operator', ['create'], ['class' => 'btn btn-success']) ?>
            </p>

            <?php
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $search,
                'columns' => [
                    [
                        'attribute' => 'id_provider',
                        'filter' => false,
                        'content' => function ($row) use ($provs) {
                            return $provs[$row['id_provider']]['name'];
                        },
                    ],
                    'name',
                    'code',
                    [
                        'attribute' => 'created_at',
                        'filter' => false,
                        'content' => function ($row) {
                            return date('Y-m-d H:i:s', strtotime($row['created_at']));
                        },
                    ],
                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]);
            ?>
        </div>
    </div>
</div>
