<?php
use yii\helpers\Html;
use common\assets\InspiniaAsset;

/**
 * @var yii\web\View $this
 * @var array        $opts
 */

$bundle = InspiniaAsset::register($this);
$this->params['subtitle'] = 'Select Country';
?>
<div class="col-lg-12">
    <div class="ibox">
        <div class="ibox-title">
            <h5>
                Select Country
            </h5>
        </div>

        <div class="ibox-content">
            <p>
                <?php
                echo Html::a(
                    'Back',
                    ['index'],
                    [
                        'class' => 'btn btn-default',
                    ]
                );
                ?>
            </p>

            <?php
            foreach ($opts['countries'] as $country) {
                echo Html::tag(
                    'p',
                    Html::a(
                        '<img src="' . $bundle->baseUrl . '/img/flags/16/' . $country['flag'] . '.png"> ' . $country['name'],
                        '/services/create?step=2&id_country=' . $country['id'],
                        [
                            'class' => 'btn btn-primary',
                        ]
                    )
                );
            }
            ?>
        </div>
    </div>
</div>
