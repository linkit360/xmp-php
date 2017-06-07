<?php
/**
 * @var $this yii\web\View
 */
$this->title = 'Status';
$this->params['subtitle'] = 'Platform infrastructure status';
$this->params['breadcrumbs'][] = $this->title;

$defPort = "50308";

$instances = [];
$instances["Yondu"] = [
    "host" => "52.76.153.135",
];

$instances["Mobilink"] = [
    "host" => "52.66.23.201",
];

$instances["Cheese"] = [
    "host" => "52.220.98.67",
];

$instances["Qrtech"] = [
    "host" => "52.220.98.67",
    "port" => "50328",
];

$instances["beeline"] = [
    "host" => "52.59.112.128",
];

foreach ($instances as $name => $params) {
    ?>
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-content">
                <h3>
                    <?= ucfirst($name) ?>
                </h3>

                <?php
                $port = $defPort;
                if (array_key_exists("port", $params)) {
                    $port = $params["port"];
                }

                $addr = $params['host'];
                $addr = "http://" . $addr . ":" . $port . "/status/get";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $addr);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 3);
                $data = curl_exec($ch);
                curl_close($ch);

                if ($data && strlen($data) > 0 && $data != "404 page not found") {
                    echo "<table class='table'>";
                    foreach (json_decode($data, true) as $type => $zzz) {
                        ?>
                        <tr>
                            <td style="width: 1%; white-space: nowrap;">
                                <?= ucfirst($type) ?>
                            </td>

                            <td>
                                <?php
                                dump(json_decode($zzz, true));
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                    echo "</table>";
                } else {
                    dump($data);
                }
                ?>
            </div>
        </div>
    </div>
    <?php
}

