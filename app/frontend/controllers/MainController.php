<?php

namespace frontend\controllers;

use const false;
use const true;
use const JSON_PRETTY_PRINT;
use function array_key_exists;
use function array_keys;
use function count;
use function date;
use function json_encode;

use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\AccessControl;

use common\models\Logs;
use common\models\LoginForm;
use common\models\Countries;
use common\models\Operators;
use common\models\Providers;
use frontend\models\TransactionsForm;
use frontend\models\LogsForm;

/**
 * Site Controller
 */
class MainController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'error',
                            'login',
                            'rpc',
                        ],
                        'allow' => true,
                    ],
                    [
                        'actions' => [
                            'index',
                            'monitoring',
                            'logout',
                            'country',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => [
                            'transactions',
                            'logs',
                        ],
                        'allow' => true,
                        'roles' => [
                            'logsView',
                        ],
                    ],
                    [
                        'allow' => false,
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function beforeAction($action)
    {
        if ($action->id === 'error' && Yii::$app->user->isGuest) {
            $this->layout = 'empty';
        }

        return parent::beforeAction($action);
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Displays monitoring page.
     *
     * @return mixed
     */
    public function actionMonitoring()
    {
        return $this->render('monitoring');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'empty';
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $log = new Logs();
            $log->controller = $this->id;
            $log->action = $this->action->id;
            $ips = $this->userIps();
            if (count($ips)) {
                $log->event = [
                    'ips' => $ips,
                ];
            }
            $log->save();

            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        if (!Yii::$app->user->isGuest) {
            $log = new Logs();
            $log->controller = $this->id;
            $log->action = $this->action->id;
            $ips = $this->userIps();
            if (count($ips)) {
                $log->event = [
                    'ips' => $ips,
                ];
            }
            $log->save();

            Yii::$app->user->logout(true);
        }

        return $this->goHome();
    }

    /**
     * Lists all Transactions models.
     * @return mixed
     */
    public function actionTransactions()
    {
        $model = new TransactionsForm();
        $model->load(Yii::$app->request->get());

        return $this->render(
            'transactions',
            [
                'model' => $model,
                'dataProvider' => $model->dataProvider(),
            ]
        );
    }

    /*
    public function actionRpc()
    {
        return;

        $str = '{"aggregated":[{"report_at":1490958234,"id_campaign":5,"provider_name":"cheese","operator_code":52000,"lp_hits":151,"lp_msisdn_hits":21,"mo":51,"mo_uniq":137,"mo_success":20,"retry_success":108,"pixels":148}]}';
        $str = json_decode($str);
        $str = json_encode($str, JSON_PRETTY_PRINT);
        echo '<h2>Array</h2><pre>';
        print_r($str);
        return;

                             [

                                 'report_at' => time(),
                                 'id_campaign' => mt_rand(1, 10),
                                 'provider_name' => 'cheese',
                                 'operator_code' => 52000,
                                 'lp_hits' => mt_rand(10, 200),
                                 'lp_msisdn_hits' => mt_rand(10, 100),
                                 'mo' => mt_rand(10, 200),
                                 'mo_uniq' => mt_rand(10, 200),
                                 'mo_success' => mt_rand(10, 200),
                                 'retry_success' => mt_rand(10, 200),
                                 'pixels' => mt_rand(10, 200),
                             ],

        $obj = new stdClass();

        $obj->report_at = time();
        $obj->id_campaign = mt_rand(1, 10);
        $obj->provider_name = 'cheese';
        $obj->operator_code = 52000;
        $obj->lp_hits = mt_rand(10, 200);
        $obj->lp_msisdn_hits = mt_rand(10, 100);
        $obj->mo = mt_rand(10, 200);
        $obj->mo_uniq = mt_rand(10, 200);
        $obj->mo_success = mt_rand(10, 200);
        $obj->retry_success = mt_rand(10, 200);
        $obj->pixels = mt_rand(10, 200);


        $obj2 = new stdClass();
        $obj2->aggregated = [
            $obj,
        ];

//        print_r($obj2);


        $data = [
            'jsonrpc' => 2.0,
            'method' => 'Aggregate.Receive',
            'id' => mt_rand(1000000000, 9999999999),
            'params' => $obj2,
        ];

        echo '<h2>Array</h2><pre>';
        print_r($data);
        echo PHP_EOL . PHP_EOL . PHP_EOL;


//        $in = json_encode($data, JSON_PRETTY_PRINT);

//        $data['params'] = $s
//        $in = $str;


        $in = '{
    "jsonrpc": 2,
    "method": "Aggregate.Receive",
    "id": 5009172138,
    "params": ' . $str . '
}';

        echo '</pre><h2>JSON</h2><pre>';
        print_r($in);

        return;


        $service_port = 10000;
        $address = gethostbyname('go');

        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket === false) {
            echo "Не удалось выполнить socket_create(): причина: " . socket_strerror(socket_last_error()) . "\n";
        } else {
//            echo "OK.\n";
        }

//        echo "Пытаемся соединиться с '$address' на порту '$service_port'...";
        $result = socket_connect($socket, $address, $service_port);
        if ($result === false) {
            echo "Не удалось выполнить socket_connect().\nПричина: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
        } else {
//            echo "OK.\n";
        }

//        echo "Отправляем запрос...";


        socket_write($socket, $in, strlen($in));
//        echo "OK.\n";
//        echo "Закрываем сокет...";
        socket_close($socket);
//        echo "OK.\n\n";
    }
    */

    /**
     * Lists all Transactions models.
     * @return mixed
     */
    public function actionLogs()
    {
        $searchModel = new LogsForm();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render(
            'logs',
            [
                'model' => $searchModel,
                'dataProvider' => $dataProvider,
            ]
        );
    }

    public function actionCountry()
    {
        $this->layout = 'empty';

        $country = Countries::find()
            ->select(
                [
                    'id',
                    'name',
                ]
            )
            ->where(
                [
                    'iso' => $_GET['iso'],
                ]
            )
            ->asArray()
            ->one();

        $providers = Providers::find()
            ->select(
                [
                    'name_alias',
                    'id',
                ]
            )
            ->where(
                [
                    'id_country' => $country['id'],
                ]
            )
            ->indexBy('name_alias')
            ->asArray()
            ->all();

        $operators = Operators::find()
            ->where([
                'id_provider' => array_keys(ArrayHelper::map($providers, 'id', 'name_alias')),
            ])
            ->orderBy('name')
            ->indexBy('code')
            ->asArray()
            ->all();

        $query = (new Query())
            ->from('xmp_reports')
            ->select([
                'SUM(lp_hits) as lp_hits',
                'SUM(mo) as mo',
                'SUM(mo_success) as mo_success',

                "date_trunc('day', report_at) as report_at_day",
                'operator_code',
            ])
            ->where([
                'AND',
                "report_at >= '" . date('Y-m-d') . "'",
                [
                    'provider_name' => array_keys($providers),
                ],
            ])
            ->groupBy([
                'operator_code',
                'report_at_day',
            ])->all();

        $data = [];
        $data['total'] = [
            'lp_hits' => 0,
            'mo' => 0,
            'mo_success' => 0,
            'name' => $country['name'],
        ];

        if (count($query)) {
            foreach ($query as $operator) {
                if (array_key_exists($operator['operator_code'], $operators)) {
                    $data[$operator['operator_code']] = [
                        'cnt' => $operator,
                        'op' => $operators[$operator['operator_code']],
                    ];
                }

                $data['total']['lp_hits'] += $operator['lp_hits'];
                $data['total']['mo'] += $operator['mo'];
                $data['total']['mo_success'] += $operator['mo_success'];
            }
        }

        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    private function userIps()
    {
        $ips = [];
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            $ips['HTTP_X_FORWARDED_FOR'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        if (array_key_exists('REMOTE_ADDR', $_SERVER)) {
            $ips['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];
        }
        return $ips;
    }
    /*

    public function actionSoap()
    {

        $server = new soap_server();
        $server->configureWSDL(
            'SOAPEventSource',
            'http://www.tibco.com/schemas/Mobilink/SharedResources/Schemas/ValueAddedServices/Schema.xsd12', '',
            'Document',
            'http://schemas.xmlsoap.org/soap/http'
        );

        // Register the method to expose
        $server->wsdl->addComplexType(
            'MyComplexType',
            'complexType',
            'struct',
            'all',
            '',
            [
                'Username' => ['name' => 'Username', 'type' => 'xsd:int'],
                'password' => ['name' => 'password', 'type' => 'xsd:string'],
            ]
        );

        $server->wsdl->addComplexType(
            'MyComplexType2',
            'complexType',
            'struct',
            'all',
            '',
            [
                'AttribElement' => [
                    'name' => 'AtribElement',
                    'type' => 'tns:MyComplexType3',
                ],
            ]
        );

        $server->wsdl->addComplexType('MyComplexType3', 'complexType', 'struct', 'all', '',
            [
                'Name' => ['name' => 'Name', 'type' => 'xsd:string'],
                'Value' => ['name' => 'Value', 'type' => 'xsd:string'],
            ]
        );
        $server->wsdl->addComplexType('MyComplexType4', 'complexType', 'struct', 'all', '',
            ['AttribElement' => ['name' => 'AtribElement', 'type' => 'tns:MyComplexType3']]
        );

        $server->wsdl->addComplexType('MyComplexType5', 'complexType', 'struct', 'all', ''
        );

        $server->wsdl->addComplexType('MyComplexType6', 'complexType', 'struct', 'all', ''
        );

        $server->wsdl->addComplexType('MyComplexType7', 'complexType', 'struct', 'all', ''
        );

        $server->register('GetSubInfo',                // method name
            [
                'Header' => 'tns:MyComplexType',
                'RequestID' => 'xsd:string',
                'Action' => 'xsd:string',
                'MSISDN' => 'xsd:string',
                'AttribList' => 'tns:MyComplexType2',
            ],    // input parameters

            [
                'Status' => 'xsd:string',
                'Reason' => 'xsd:string',
                'Attrib1' => 'xsd:string',
                'Attrib2' => 'xsd:string',
                'Attrib3' => 'xsd:string',
                'Attrib4' => 'xsd:string',
                'AttribList' => 'tns:MyComplexType4',
            ],    // output parameters
            'http://www.tibco.com/schemas/Mobilink/SharedResources/Schemas/ValueAddedServices/Schema.xsd12',// namespace
            'VASInfo.com/Services/GetSubInfo',                // soapaction
            'document',                                // style
            'Says hello to the caller'            // documentation
        );

// Use the request to (try to) invoke the service
        if (!isset($HTTP_RAW_POST_DATA)) {
            $HTTP_RAW_POST_DATA = file_get_contents('php://input');
        }
        $server->service($HTTP_RAW_POST_DATA);
// Define the method as a PHP function
        function GetSubInfo($Header, $RequestID, $Action, $MSISDN, $AttribList)
        {
            $UsernameOK = $Header['Username'];
            $passwordOK = $Header['Password'];
            $Name = ($AttribList['AttribElement']['Name']);
            $Value = ($AttribList['AttribElement']['Value']);
            $Attrib1 = $MSISDN;

            if ($UsernameOK == "slypee_mob" && $passwordOK == "Slyp33M0b1423!") {
//	$conn = mysql_connect("172.31.23.89", "pakistan1", "pakistan1");
//	$db = mysql_select_db("pakistan_content");
//	$result = mysql_query("SELECT * FROM tbl_subscriber where msisdn='".$MSISDN."' limit 1");

//	$result = curl();

                // Find data by msisdn
                $ch = curl_init("http://xmp.linkit360.ru/api/pk-wsdl-get-data?msisdn=" . $MSISDN);
                curl_setopt($ch, CURLOPT_HEADER, 0);
//        curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
                $result = curl_exec($ch);
                curl_close($ch);


                if (empty($result)) {
                    return new soap_fault('faultcode', '', 'no result!', '');
                }

                if ($row = unserialize($result)) {
                    $reg_date = $row["created_at"];
                    $msisdn = $row["msisdn"];

                    if ($msisdn == "") {
                        return new soap_fault('faultcode', '', 'not found msisdn!', '');
                    }
                    if ($msisdn != "") {

                        $status = "OK";
                        $reason = $row["result"] == 'paid' ? 'Subscriber is inactive' : 'Subscriber is active';
                        $att1 = 'Subscription Date:' . $row["created_at"];
                        $att1 = $row['created_at'] == null ? '-' : 'Subscription Date:' . date("g/d/Y h:i:s A",
                                strtotime($row['created_at']));
                        $att2 = $row['last_pay_attempt_at'] == null ? '-' : 'Unsubscription Date:' . date("g/d/Y h:i:s A",
                                strtotime($row['last_pay_attempt_at']));
                        $att3 = "Service Name:Mobilink auto renewal mobile academy";
                        $data = [
                            'Status' => $status,
                            'Reason' => $reason,
                            'Attrib1' => $att1,
                            'Attrib2' => $att2,
                            'Attrib3' => $att3,
                            'Attrib4' => '',
                            'AttribList' => [
                                'AttribElement' => ['Name' => 'Subscription Date', 'Value' => $att1],
                                'AttribElement' => ['Name' => 'Unsubscription Date', 'Value' => $att2],
                                'AttribElement' => [
                                    'Name' => 'Service Name',
                                    'Value' => 'Mobilink auto renewal mobile academy',
                                ],
                            ],
                        ];
                    } else {
                        $status = "NOK";
                        $Name = "NO ServiceName";
                        $Value = "NO Value";
                        $Attrib1 = "Slypee";
                        $data = [
                            'status' => $status,
                            'Reason' => $reason,
                            'Attrib1' => $Attrib1,
                            'Attrib2' => '',
                            'Attrib3' => $att3,
                            'Attrib4' => '',
                            'AttribList' => ['AttribElement' => ['Name' => $Name, 'Value' => $Value]],
                        ];
                    }

                    return $data;
                }

                $status = "NOK";
                $Name = "NO ServiceName";
                $Value = "NO Value";
                $Attrib1 = "Slypee";
                $data = [
                    'status' => $status,
                    'Reason' => '',
                    'Attrib1' => $Attrib1,
                    'Attrib2' => '',
                    'Attrib3' => '',
                    'Attrib4' => '',
                    'AttribList' => ['AttribElement' => ['Name' => $Name, 'Value' => $Value]],
                ];
                return $data;
            } else {
                return new soap_fault('faultcode', '', 'username or password not found!' . $UsernameOK, '');
            }//end username & password
        }


    }
*/

    /*
    $MSISDN = '92000000000';

$ch = curl_init("http://xmp.linkit360.ru/api/pk-wsdl-get-data?msisdn=".$MSISDN);
        curl_setopt($ch, CURLOPT_HEADER, 0);
//        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        $result = curl_exec($ch);
        curl_close($ch);

    if (empty($result)){
	return new soap_fault('faultcode', '', 'no result!', '');
    }

    if ($row = unserialize($result)) {

	print_r($row['msisdn']);

    }

     */

}
