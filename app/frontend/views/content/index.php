<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\daterange\DateRangePicker;
use kartik\widgets\Select2;

/**
 * @var yii\web\View                $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var array                       $data
 * @var array                       $users
 */

$this->title = 'Content';
$this->params['subtitle'] = 'Service content';
$this->params['breadcrumbs'][] = $this->title;

$helper = new \common\helpers\ModalHelper();
$helper->modalDelete($this);

$columns = [
    [
        'class' => \yii\grid\SerialColumn::class,
        'contentOptions' => [
            'style' => 'width: 1%; white-space: nowrap;',
        ],
    ],
    'title',
    [
        'attribute' => 'id_category',
        'contentOptions' => [
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'content' => function ($row) use ($data) {
            if (array_key_exists($row['id_category'], $data['cats'])) {
                return $data['cats'][$row['id_category']]->title;
            }

            return '';
        },
    ],
    [
        'attribute' => 'id_publisher',
        'contentOptions' => [
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'content' => function ($row) use ($data) {
            if (array_key_exists($row['id_publisher'], $data['pubs'])) {
                return $data['pubs'][$row['id_publisher']]->title;
            }

            return '';
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
        'attribute' => 'time_create',
        'contentOptions' => [
            'style' => 'width: 1%; white-space: nowrap;',
        ],
        'content' => function ($row) {
            return date('Y-m-d H:i:s', strtotime($row['time_create']));
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
                <div class="col-lg-6">
                    <?php
                    echo $form->field($model, 'id_category')->widget(
                        Select2::classname(),
                        [
                            'data' => $data['id_category'],
                            'options' => [
                                'placeholder' => 'Category',
                            ],
                        ]
                    );
                    ?>
                </div>

                <div class="col-lg-6">
                    <?php
                    echo $form->field($model, 'id_publisher')->widget(
                        Select2::classname(),
                        [
                            'data' => $data['id_publisher'],
                            'options' => [
                                'placeholder' => 'Publisher',
                            ],
                        ]
                    );
                    ?>
                </div>

                <div class="col-lg-6">
                    <?php
                    echo $form->field($model, 'title');
                    ?>
                </div>

                <div class="col-lg-6">
                    <?php
                    echo $form->field($model, 'date_range')->widget(
                        DateRangePicker::classname(),
                        [
                            'convertFormat' => true,
                            'options' => [
                                'class' => 'form-control',
                                'placeholder' => 'Date/Time',
                            ],
                            'pluginOptions' => [
                                'timePicker' => true,
                                'timePickerIncrement' => 15,
                                'locale' => ['format' => 'Y-m-d h:i A'],
                            ],
                        ]
                    );
                    ?>
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
                <?= Html::a('Add Content', ['create'], ['class' => 'btn btn-success']) ?>
                <?= Html::a('Categories', '/content-categories/index', ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Publishers', '/content-publishers/index', ['class' => 'btn btn-primary']) ?>
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
