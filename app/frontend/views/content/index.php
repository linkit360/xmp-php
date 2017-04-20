<?php
use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View                $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var array                       $data
 */

$this->title = 'Content';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-lg-8">
    <div class="ibox">
        <div class="ibox-content">
            <p>
                <?= Html::a('Add Content', ['create'], ['class' => 'btn btn-success']) ?>
                <?= Html::a('Categories', '/content-categories/index', ['class' => 'btn btn-info']) ?>
                <?= Html::a('Publishers', '/content-publishers/index', ['class' => 'btn btn-info']) ?>
            </p>

            <?php
            echo GridView::widget(
                [
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        [
                            'attribute' => 'id_category',
                            'content' => function ($row) use ($data) {
                                if (array_key_exists($row['id_category'], $data['cats'])) {
                                    return $data['cats'][$row['id_category']]->title;
                                }
                                return '';
                            },
                        ],
                        [
                            'attribute' => 'id_publisher',
                            'content' => function ($row) use ($data) {
                                if (array_key_exists($row['id_publisher'], $data['pubs'])) {
                                    return $data['pubs'][$row['id_publisher']]->title;
                                }
                                return '';
                            },
                        ],

                        'title',
                        [
                            'attribute' => 'time_create',
                            'content' => function ($row) {
                                return date('Y-m-d H:i:s', strtotime($row['time_create']));
                            },
                        ],
                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]
            );
            ?>
        </div>
    </div>
</div>
