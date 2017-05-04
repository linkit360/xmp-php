<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

use kartik\widgets\Select2;

/**
 * @var yii\web\View                $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var array                       $users
 */

$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;

$helper = new \common\helpers\ModalHelper();
$helper->modalDelete($this);

$columns = [
    [
        'attribute' => 'name',
        'filter' => false,
        'content' => function ($row) {
            return '<i class="fa ' . $row['icon'] . '" aria-hidden="true"></i>&nbsp;' . $row['title'];
        },
    ],
];

if (count($users)) {
    $columns[] = [
        'attribute' => 'id_user',
        'filter' => false,
        'contentOptions' => [
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'content' => function ($row) use ($users) {
            return Html::a(
                $users[$row['id_user']],
                '/users/' . $row['id_user'],
                [
                    'target' => '_blank',
                ]
            );
        },
    ];
}

$columns = array_merge(
    $columns,
    [
        [
            'contentOptions' => [
                'style' => 'width: 1%; white-space: nowrap;',
            ],
            'content' => function ($row) {
                $html = Html::a(
                    'View',
                    [
                        'view',
                        'id' => $row['id'],
                    ],
                    [
                        'class' => 'btn btn-xs btn-success',
                    ]
                );

                $html .= '&nbsp;';
                $html .= Html::a(
                    'Update',
                    [
                        'update',
                        'id' => $row['id'],
                    ],
                    [
                        'class' => 'btn btn-xs btn-primary',
                    ]
                );

                $html .= '&nbsp;';
                $html .= Html::button(
                    'Delete',
                    [
                        'class' => 'btn btn-xs btn-danger',
                        'data-toggle' => 'modal',
                        'data-target' => '#modalDelete',
                        'data-rowid' => $row['id'],
                    ]
                );

                return $html;
            },
        ],
    ]);


$form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
]);
?>
<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-content">
            <div class="row">
                <div class="col-lg-12">
                    <?= $form->field($model, 'title') ?>
                </div>

                <div class="col-lg-12">
                    <?php
                    if (count($users)) {
                        echo $form->field($model, 'id_user')->widget(
                            Select2::classname(),
                            [
                                'data' => $users,
                                'options' => [
                                    'placeholder' => 'User',
                                ],
                            ]
                        );
                    }
                    ?>
                </div>

                <div class="col-lg-12 text-right">
                    <?php
                    echo Html::a(
                        'Reset',
                        ['index'],
                        ['class' => 'btn btn-default']
                    );
                    echo '&nbsp;';
                    echo Html::submitButton(
                        'Search',
                        ['class' => 'btn btn-primary']
                    );
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
ActiveForm::end();
echo '</div><div class="row">';
?>

<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-content">
            <p>
                <?php
                echo Html::a(
                    'Create Category',
                    ['create'],
                    ['class' => 'btn btn-success']
                );
                ?>
            </p>

            <?php
            echo GridView::widget(
                [
                    'dataProvider' => $dataProvider,
                    'columns' => $columns,
                ]
            );
            ?>
        </div>
    </div>
</div>
