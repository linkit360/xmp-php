<?php

namespace frontend\controllers;

use const PHP_EOL;
use function print_r;
use function str_replace;
use function htmlspecialchars;

use SoapClient;
use SoapServer;

use yii\web\Controller;

use common\helpers\SoapHelper;

class SoapController extends Controller
{
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        $this->layout = 'empty';

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $server = new SoapServer(
            __DIR__ . '/../../GetSubInfo.wsdl',
            [
                'uri' => 'http://' . $_SERVER['SERVER_ADDR'] . ':8080/soap/server',
            ]
        );

        $server->setObject(new SoapHelper());
        $server->handle(file_get_contents('php://input'));
    }

    public function actionCall()
    {
        ini_set('soap.wsdl_cache_enabled', 0);
        ini_set('soap.wsdl_cache_ttl', 0);

        $client = new SoapClient(
            __DIR__ . '/../../GetSubInfo.wsdl',
            [
                'soap_version' => SOAP_1_1,
                'location' => 'http://' . $_SERVER['SERVER_ADDR'] . ':8080/soap/index',
                'uri' => 'http://' . $_SERVER['SERVER_ADDR'] . ':8080/soap/index',
                'cache_wsdl' => WSDL_CACHE_NONE,
                'trace' => true,
                'exceptions' => true,
            ]
        );

        echo "<pre><h1>KO?</h1>";
        $obj = [
            'Header' => [
                'Username' => 'slypee_mod',
                'Password' => 'Slyp33mOb1423!',
            ],
            'RequestID' => 'test-123',
            'Action' => 'SubInfo',
            'MSISDN' => '923000854058',
            'AttribList' => [
                'AttribElement' => [
                    'Name' => 'ServiceName',
                    'Value' => 'Slypee',
                ],
            ],
        ];
        print_r($client->GetSubInfo($obj));
        $resp = htmlspecialchars($client->__getLastResponse());
        print_r(str_replace('&lt;', PHP_EOL . '&lt;', $resp));

        echo "<hr/><h1>OK?</h1>";
        $obj = [
            'Header' => [
                'Username' => 'slypee_mod',
                'Password' => 'Slyp33mOb1423!',
            ],
            'RequestID' => 'test-123',
            'Action' => 'SubInfo',
            'MSISDN' => '923088290736',
            'AttribList' => [
                'AttribElement' => [
                    'Name' => 'ServiceName',
                    'Value' => 'Slypee',
                ],
            ],
        ];
        print_r($client->GetSubInfo($obj));
        $resp = htmlspecialchars($client->__getLastResponse());
        print_r(str_replace('&lt;', PHP_EOL . '&lt;', $resp));
        echo "</pre>";
    }
}
