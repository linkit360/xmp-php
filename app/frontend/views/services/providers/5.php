<?php
/**
 * @var yii\web\View           $this
 * @var array                  $models
 * @var array                  $opts
 * @var yii\widgets\ActiveForm $form
 */

# Mobilink
use kartik\widgets\Select2;

$this->registerJs('ch();', $this::POS_READY);

/** @var \frontend\models\Services\MobilinkForm $model */
$model = $models['model_provider'];

echo $form->field($model, 'sms_on_content')->textInput(['maxlength' => true]);
echo $form->field($model, 'sms_on_subscribe')->textInput(['maxlength' => true]);
echo $form->field($model, 'sms_on_unsubscribe')->textInput(['maxlength' => true]);
?>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'retry_days') ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'inactive_days') ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'grace_days') ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'minimal_touch_times') ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'trial_days') ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'purge_after_days') ?>
    </div>
</div>
<?php
echo $form->field($model, 'periodic_days_type')
    ->dropDownList(
        [
            "off" => "OFF",
            "weekly" => "Weekly",
            "any" => "Every Day",
            "days" => "Select Days",
        ],
        [
            'onchange' => 'ch();',
        ]
    );

//if ($model->periodic_days) {
//    $model->periodic_days = json_decode($model->periodic_days);
//}

echo $form
    ->field($model, 'periodic_days_sel')
    ->widget(
        Select2::classname(),
        [
            'data' => [
                "mon" => "Monday",
                "tue" => "Tuesday",
                "wed" => "Wednesday",
                "thu" => "Thursday",
                "fri" => "Friday",
                "sat" => "Saturday",
                "sun" => "Sunday",
            ],
            'showToggleAll' => true,
            'hideSearch' => true,
            'maintainOrder' => true,
            'options' => [
                'placeholder' => 'Select Days',
                'multiple' => true,
            ],
            'pluginOptions' => [
                'tags' => true,
                'maximumInputLength' => 7,
            ],
        ]
    );
?>
<script type="text/javascript">
    function ch() {
        var sel = $("#mobilinkform-periodic_days_sel");
        var val = $("#mobilinkform-periodic_days_type").val();
        sel.prop("disabled", (val !== "days"));
    }
</script>
