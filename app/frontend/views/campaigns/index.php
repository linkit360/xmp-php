<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\SerialColumn;
use yii\widgets\ActiveForm;

use kartik\widgets\Select2;

/**
 * @var yii\web\View                $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var array                       $data
 * @var array                       $users
 * @var array                       $srv
 */

$this->title = 'Campaigns';
$this->params['subtitle'] = 'URL and LP manager for services';
$this->params['breadcrumbs'][] = $this->title;

$helper = new \common\helpers\ModalHelper();
$helper->modalDelete($this);

$columns = [
    [
        'class' => SerialColumn::class,
        'contentOptions' => [
            'style' => 'width: 1%; white-space: nowrap;',
        ],
    ],
    'title',
    [
        'attribute' => 'link',
        'visible' => count($users),
        'filter' => false,
        'headerOptions' => [
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'contentOptions' => [
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'content' => function ($row) use ($srv) {
            if (array_key_exists($row["id_service"], $srv)) {
                $url = "http://" . $srv[$row["id_service"]] . "/" . $row["link"];

                return Html::a(
                    "/" . $row["link"],
                    $url,
                    [
                        "target" => "_blank",
                        "title" => $url,
                    ]
                );
            }

            return "-";
        },
    ],
    [
        'attribute' => 'id_user',
        'visible' => count($users),
        'filter' => false,
        'headerOptions' => [
            'style' => 'width: 1%; white-space: nowrap;',
        ],
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
    ],
    [
        'attribute' => 'created_at',
        'contentOptions' => [
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'content' => function ($data) {
            return date('Y-m-d H:i:s', strtotime($data['created_at']));
        },
    ],
    [
        'attribute' => 'updated_at',
        'contentOptions' => [
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'content' => function ($data) {
            return date('Y-m-d H:i:s', strtotime($data['updated_at']));
        },
    ],
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

            if (!$row->status) {
                return $html;
            }

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
];

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
                        echo $form->field($model, 'id_user')
                            ->widget(
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
?>

<div class="col-lg-8">
    <div class="ibox">
        <div class="ibox-content">
            <p>
                <?= Html::a('Create Campaign', ['create'], ['class' => 'btn btn-success']) ?>
            </p>

            <?php
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'rowOptions' => function ($model) {
                    return [
                        "class" => $model->status ?: "danger",
                    ];
                },
                'columns' => $columns,
            ]);
            ?>
        </div>
    </div>
</div>
