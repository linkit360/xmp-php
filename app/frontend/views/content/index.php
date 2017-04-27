<?php
use common\models\Content\Categories;
use common\models\Content\Publishers;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use kartik\widgets\Select2;
use kartik\daterange\DateRangePicker;
use common\models\Content\Content;

/**
 * @var yii\web\View                $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var array                       $data
 */

$this->title = 'Content';
$this->params['subtitle'] = 'Service content';
$this->params['breadcrumbs'][] = $this->title;

$formData = [];
$formData['id_category'] = Content::find()
    ->select('id_category')
    ->groupBy('id_category')
    ->indexBy('id_category')
    ->column();

$formData['id_category'] = Categories::find()
    ->select([
        'title',
        'id',
    ])
    ->where([
        'id' => array_keys($formData['id_category']),
    ])
    ->indexBy('id')
    ->column();


$formData['id_publisher'] = Content::find()
    ->select('id_publisher')
    ->where("id_publisher IS NOT NULL")
    ->groupBy('id_publisher')
    ->indexBy('id_publisher')
    ->column();

$formData['id_publisher'] = Publishers::find()
    ->select([
        'title',
        'id',
    ])
    ->where([
        'id' => array_keys($formData['id_publisher']),
    ])
    ->indexBy('id')
    ->column();

$form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
]);
?>
<div class="col-lg-7">
    <div class="ibox">
        <div class="ibox-content">
            <div class="row">
                <div class="col-lg-6">
                    <?php
                    echo $form->field($model, 'id_category')->widget(
                        Select2::classname(),
                        [
                            'data' => $formData['id_category'],
                            'options' => [
                                'placeholder' => 'Category',
                            ],
                            'pluginOptions' => [
                                'escapeMarkup' => new JsExpression("function(m) { return m; }"),
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
                            'data' => $formData['id_publisher'],
                            'options' => [
                                'placeholder' => 'Publisher',
                            ],
                            'pluginOptions' => [
                                'escapeMarkup' => new JsExpression("function(m) { return m; }"),
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
                    echo $form->field($model, 'date_range')
                        ->widget(
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
                    <br/>
                </div>

                <div class="col-lg-6">
                    <?php
                    echo Html::submitButton(
                        'Search',
                        [
                            'class' => 'btn btn-primary',
                        ]
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

<div class="col-lg-7">
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
                'columns' => [
                    'title',
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
                    [
                        'attribute' => 'time_create',
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
                            $html .= Html::a(
                                'Delete',
                                [
                                    'delete',
                                    'id' => $row['id'],
                                ],
                                [
                                    'class' => 'btn btn-xs btn-danger',
                                ]
                            );

                            return $html;
                        },

                    ],
                ],
            ]);
            ?>
        </div>
    </div>
</div>
