<?php
/**
 * @var $this yii\web\View
 */

\frontend\assets\DashboardAsset::register($this);

$this->title = 'Dashboard';
$this->params['subtitle'] = 'Reports and Stats for Today';
$this->params['breadcrumbs'][] = $this->title;

$addr = 'ws://' . $_SERVER['HTTP_HOST'];
if (YII_ENV === "testing") {
    $addr = "ws://ws.test-xmp2.linkit360.ru";
}

if (YII_ENV === "production") {
    $addr = "ws://ws.xmp2.linkit360.ru";
}

$this->registerJs('server = "' . $addr . ':2082/echo";');
unset($addr);
?>
<div class="col-lg-3">
    <div class="ibox">
        <div class="ibox-content">
            <h1 class="no-margins" id="output_lp">
                0
            </h1>

            LP Hits

            <div class="pull-right">
                <span data-diameter="40" class="output_lp_chart">0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0</span>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-3">
    <div class="ibox">
        <div class="ibox-content">
            <h1 class="no-margins" id="output_mo">
                0
            </h1>

            Total MO

            <div class="pull-right">
                <span data-diameter="40" class="output_mo_chart">0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0</span>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-3">
    <div class="ibox">
        <div class="ibox-content">
            <h1 class="no-margins" id="output_mos">
                0
            </h1>

            Success MO

            <div class="pull-right">
                <span data-diameter="40" class="output_mos_chart">0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0</span>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-3">
    <div class="ibox">
        <div class="ibox-content">
            <h1 class="no-margins" id="output_conv">
                0%
            </h1>

            Conversion Rate

            <div class="pull-right">
                <span data-diameter="40"
                      class="output_conv_chart">0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0</span>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="ibox">
        <div class="ibox-content">
            <div id="world-map"></div>
        </div>
    </div>
</div>

<h3 style="margin-left: 17px;">LP Hits by Country</h3>

<div id="summary">

</div>

<?php
/*
if (Yii::$app->user->can('monitoringView')) {
    ?>
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-content">
                <div id="logs"></div>
            </div>
        </div>
    </div>
    <?php
}
*/
# 'View Country' Modal window
?>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="color-line"></div>
            <div class="modal-header text-center">
                <h4 class="modal-title" id="modal_output_name">
                    Country
                </h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="ibox">
                            <div class="ibox-content">
                                <h1 class="no-margins" id="modal_output_lp">
                                    0
                                </h1>

                                LP Hits
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="ibox">
                            <div class="ibox-content">
                                <h1 class="no-margins" id="modal_output_mo">
                                    0
                                </h1>

                                Total MO
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="ibox">
                            <div class="ibox-content">
                                <h1 class="no-margins" id="modal_output_mos">
                                    0
                                </h1>

                                Success MO
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="ibox">
                            <div class="ibox-content">
                                <h1 class="no-margins" id="modal_output_conv">
                                    0%
                                </h1>

                                Conversion Rate
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <table id="modal_output_table" class="table">
                            <thead>
                            <tr>
                                <th>Operator</th>
                                <th class="text-right">LP Hits</th>
                                <th class="text-right">Total MO</th>
                                <th class="text-right">Success MO</th>
                                <th class="text-right">Conversion Rate</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
