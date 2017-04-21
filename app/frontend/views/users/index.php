<?php
use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View                $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var \frontend\models\UsersForm  $searchModel
 */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-lg-8">
    <div class="ibox">
        <div class="ibox-content">
            <p>
                <?php
                if (Yii::$app->user->can('usersCreate')) {
                    echo '&nbsp;' . Html::a('Create User', ['create'], ['class' => 'btn btn-success']);
                }
                ?>
            </p>

            1
            2
            3
            4
            <?php
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'username',
                    'email:email',
                    [
                        'attribute' => 'created_at',
                        'format' => 'datetime',
                        'filter' => false,
                    ],
                    [
                        'attribute' => 'updated_at',
                        'format' => 'datetime',
                        'filter' => false,
                    ],
                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]);
            ?>
        </div>
    </div>
</div>
