<?php

use common\models\Countries;
use common\models\Providers;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 */
$bundle = \common\assets\InspiniaAsset::register($this);
$flags = new \common\helpers\FlagsHelper();

$this->params['subtitle'] = 'Select Country';

$providers = Providers::find()
    ->select('id_country')
    ->groupBy('id_country')
    ->asArray()
    ->column();

$countries = Countries::find()
    ->select(
        [
            'id',
            'name',
            'flag',
        ]
    )
    ->where(
        [
            'id' => $providers,
        ]
    )
    ->orderBy('name')
    ->all();
?>
<div class="col-lg-12">
    <div class="ibox">
        <div class="ibox-title">
            <h5>
                Select Country
            </h5>
        </div>

        <div class="ibox-content">
            <?php
            foreach ($countries as $country) {
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
