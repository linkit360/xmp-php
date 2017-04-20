<?php
use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View                $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var \common\models\Providers    $search
 */
$this->title = 'Providers';
$this->params['breadcrumbs'][] = $this->title;

$countries = \common\models\Countries::find()
    ->indexBy('id')
    ->asArray()
    ->all()
?>
<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-content">
            <p>
                <?php
                echo Html::a(
                    'Create Provider',
                    ['create'],
                    ['class' => 'btn btn-success']
                );
                ?>
            </p>

            <?php
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $search,
                'columns' => [
//                    'id',
                    'name',
                    'name_alias',
                    [
                        'attribute' => 'id_country',
                        'filter' => false,
                        'content' => function ($row) use ($countries) {
                            return $countries[$row['id_country']]['name'];
                        },
                    ],

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]);
            ?>
        </div>
    </div>
</div>
