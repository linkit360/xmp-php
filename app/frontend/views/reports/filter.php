<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\widgets\DatePicker;

/**
 * @var yii\web\View                $this
 * @var frontend\models\ReportsForm $model
 */

$form = ActiveForm::begin([
    'action' => '/' . Yii::$app->request->pathInfo,
    'method' => 'get',
]);
?>
    <script type="text/javascript">
        var struct = {};
        <?= 'struct = ' . json_encode($model->getStruct(), JSON_PRETTY_PRINT) . ';' ?>

        function checkForm(obj) {
            var country = $('#reportsform-country');
            var provider = $('#reportsform-provider');
            var operator = $('#reportsform-operator');

            var countryId = parseInt(country.val());
            var providerId = parseInt(provider.val());

            if ($(obj).attr('id') === 'reportsform-country') {
                provider.empty();
                provider.append($('<option value=0>All</option>'));

                operator.empty();
                operator.append($('<option value=0>All</option>'));

                if (countryId !== 0) {
                    $.each(struct[countryId]['items'], function (index, value) {
                        provider.append($('<option value=' + index + '>' + value['name'] + '</option>'));
                    });
                }

                return true;
            }

            if ($(obj).attr('id') === 'reportsform-provider') {

                operator.empty();
                operator.append($('<option value=0>All</option>'));

                if (providerId !== 0) {
                    $.each(struct[countryId]['items'][providerId]['items'], function (index, value) {
                        operator.append($('<option value=' + index + '>' + value + '</option>'));
                    });
                }

                return true;
            }

            return false;
        }
        //    checkForm();
    </script>

    <div class="row">
        <div class="col-md-6">
            <?php
            echo $form
                ->field($model, 'country')
                ->dropDownList(
                    [0 => 'All'] +
                    ArrayHelper::map(
                        $model->getCountries(),
                        'id',
                        'name'
                    ),
                    [
                        'onchange' => 'checkForm(this);',
                    ]
                );
            ?>
        </div>

        <div class="col-md-6">
            <?php
            $providers = [];
            if ($model->country !== null && $model->country !== "0") {
                foreach ($model->getStruct()[$model->country]['items'] as $providerId => $provider) {
                    $providers[$providerId] = $provider['name'];
                }
            }

            echo $form
                ->field($model, 'provider')
                ->dropDownList(
                    [0 => 'All'] + $providers,
                    [
                        'onchange' => 'checkForm(this);',
                    ]
                );
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?php
            $operators = [];
            if (
                $model->country !== null &&
                $model->country !== "0" &&
                $model->provider !== null &&
                $model->provider !== "0"
            ) {
                $operators = $model->getStruct()[$model->country]['items'][$model->provider]['items'];
            }

            echo $form
                ->field($model, 'operator')
                ->dropDownList(
                    [0 => 'All'] + $operators,
                    [
                        'onchange' => 'checkForm(this);',
                    ]
                );
            ?>
        </div>

        <div class="col-md-6">
            <?php
            echo $form
                ->field($model, 'campaign')
                ->dropDownList(
                    [0 => 'All'] +
                    $model->getCampaigns()
                );
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php
            echo DatePicker::widget([
                'type' => DatePicker::TYPE_RANGE,
                'form' => $form,
                'model' => $model,
                'attribute' => 'dateFrom',
                'attribute2' => 'dateTo',
                'options' => ['placeholder' => 'Start date'],
                'options2' => ['placeholder' => 'End date'],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'autoclose' => true,
                ],
            ]);

            echo '<br/>';
            echo Html::submitButton(
                'Search',
                [
                    'class' => 'btn btn-success',
                ]
            );
            ?>
        </div>
    </div>
<?php
ActiveForm::end();
