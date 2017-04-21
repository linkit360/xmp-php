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
?>
<div class="col-lg-8">
    <div class="ibox">
        <div class="ibox-content">
            <?php
            $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
            ]);

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

            echo $form->field($model, 'title');
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

            echo '<br/>';
            echo Html::submitButton('Search', ['class' => 'btn btn-primary']);
            ActiveForm::end();
            ?>
        </div>
    </div>
</div>


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
