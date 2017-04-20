<?php
use kartik\widgets\DatePicker;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View                     $this
 * @var yii\data\ActiveDataProvider      $dataProvider
 * @var frontend\models\TransactionsForm $model
 */
$this->title = 'Logs';
$this->params['subtitle'] = 'Transactions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-title">
            <h5>
                Filter
            </h5>
        </div>

        <div class="ibox-content">
            <?php
            $form = ActiveForm::begin([
                'action' => '/logs',
                'method' => 'get',
            ]);
            ?>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'msisdn')->textInput() ?>
                </div>

                <div class="col-md-6">
                    <?php
                    echo $form->field($model, 'date')->widget(DatePicker::classname(), [
                        'options' => ['placeholder' => 'Date'],
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'autoclose' => true,
                        ],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?php
                    echo $form->field($model, 'country')->dropDownList($model->countries);
                    echo '<br/>' . Html::submitButton('Search', [
                            'class' => 'btn btn-info',
                        ]);
                    ?>
                </div>
            </div>
            <?php
            ActiveForm::end();
            ?>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="ibox">
        <div class="ibox-content">
            <?php
            echo GridView::widget(
                [
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        'created_at',
                        'sent_at',
                        'tid',
                        'msisdn',
                        'id_country',
                        'id_service',
                        'id_campaign',
                        'id_subscription',
                        'id_content',
                        'id_operator',
                        'operator_token',
                        'price',
                        'result',
                    ],
                ]
            );
            ?>
        </div>
    </div>
</div>
