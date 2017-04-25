<?php

namespace frontend\controllers;

use function print_r;
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
//            null,
            [
                'uri' => 'http://' . $_SERVER['SERVER_ADDR'] . ':8080/soap/server',
            ]
        );

        $server->setObject(new SoapHelper());

        $server->handle(file_get_contents('php://input'));

//        echo 'Start';

        die;


        $server = new nusoap_server(__DIR__ . '/../../GetSubInfo.wsdl');
//        $server->configureWSDL(
//            'SOAPEventSource',
//            'http://www.tibco.com/schemas/Mobilink/SharedResources/Schemas/ValueAddedServices/Schema.xsd12',
//            '',
//            'Document',
//            'http://schemas.xmlsoap.org/soap/http'
//        );

        if (!isset($HTTP_RAW_POST_DATA)) {
            $HTTP_RAW_POST_DATA = file_get_contents('php://input');
        }


        $server->service($HTTP_RAW_POST_DATA);


        /*
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
        */
        /*

        $server->register(
            'GetSubInfo',
            [
                'Header' => 'tns:MyComplexType',
                'RequestID' => 'xsd:string',
                'Action' => 'xsd:string',
                'MSISDN' => 'xsd:string',
                'AttribList' => 'tns:MyComplexType2',
            ],
            [
                'Status' => 'xsd:string',
                'Reason' => 'xsd:string',
                'Attrib1' => 'xsd:string',
                'Attrib2' => 'xsd:string',
                'Attrib3' => 'xsd:string',
                'Attrib4' => 'xsd:string',
                'AttribList' => 'tns:MyComplexType4',
            ],
            'http://www.tibco.com/schemas/Mobilink/SharedResources/Schemas/ValueAddedServices/Schema.xsd12',
            'VASInfo.com/Services/GetSubInfo',
            'document',
            ''
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
*/

    }

    public function actionCall()
    {
        ini_set('soap.wsdl_cache_enabled', 0);
        ini_set('soap.wsdl_cache_ttl', 0);

        $client = new SoapClient(
            __DIR__ . '/../../GetSubInfo.wsdl',
//            null,
            [
//                'soap_version' => SOAP_1_2,
                'soap_version' => SOAP_1_1,
                'location' => 'http://' . $_SERVER['SERVER_ADDR'] . ':8080/soap/index',
                'uri' => 'http://' . $_SERVER['SERVER_ADDR'] . ':8080/soap/index',
                'cache_wsdl' => WSDL_CACHE_NONE,
                'trace' => true,
                'exceptions' => true,
            ]
        );

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
//        dump($client->__getFunctions());


        print_r($client->GetSubInfo($obj));

        print_r(
            $client->__getLastResponse()
        );
        die;

        $client = new nusoap_client('http://172.18.0.4:8080/soap/server');

        $result = $client->call('hello');

        print_r($result);
        if ($client->fault) {
            echo '<p><b>Сбой: ';
            print_r($result);
            echo '</b></p>';
        } else {
            // Проверяем, не произошла ли ошибка
            $err = $client->getError();
            if ($err) {
                // Отображаем ошибку
//                echo '<p><b>Ошибка: ' . $err . '</b></p>';

                echo($client->response);

            } else {
                // Отображаем результат
                print_r($result);
            }
        }
        die;

        $client = new nusoap_client('127.0.0.1/main/soap');
        $client->namespaces = [
            'soapenv' => 'http://schemas.xmlsoap.org/soap/envelope/',
            'sch' => 'http://www.tibco.com/schemas/Mobilink/SharedResources/Schemas/ValueAddedServices/Schema.xsd12',
        ];

//        $client->soap_defencoding = 'UTF-8';
//        $client->decode_utf8 = false;

// Calls
        $result = $client->call(
            'GetSubInfo',
            [
                'Header' => [
                    'Username' => 'slypee_mod',
                    'Password' => 'Slyp33mOb1423!',
                ],
            ],
            'sch',
            'SubInfo'
        );


        dump($result);
        dump($client->request);
        dump($client->response);
        print_r($client->getDebug());
        /*

        1. We are not getting the response as per requirement. We required SubInfoOut  that's why received output data invalid.. Kindly update your WSDL accordingly. Please confirm once done.​

         Required:​

         SubInfoOut" xmlns="http://www.tibco.com/schemas/Mobilink/SharedResources/Schemas/ValueAddedServices/Schema.xsd12">
                 <status xsi:type="xsd:string">OK</status>

        <GetSubInfoOut xsi:type="sch:GetSubInfoOut" xmlns="http://www.tibco.com/schemas/Mobilink/SharedResources/Schemas/ValueAddedServices/Schema.xsd12">
                 <Status xsi:nil="true" xsi:type="xsd:string"/>​

        Current:

         GetSubInfoOut" xmlns="http://www.tibco.com/schemas/Mobilink/SharedResources/Schemas/ValueAddedServices/Schema.xsd12">
                 <status xsi:type="xsd:string">OK</status>

        2. PFA the WSDL file.

        Required Possible values in action:

        ·         SubInfo (to check if customer has a product)
        ·         UnSubscribe (to un-subscribe the product)


        3. PFB the Sample  Request and response for your reference. In case of any query please discuss.

        Action in Request : SubInfo or UNSUB


        Request:


        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sch="http://www.tibco.com/schemas/Mobilink/SharedResources/Schemas/ValueAddedServices/Schema.xsd12">
           <soapenv:Header/>​
           <soapenv:Body>
              <sch:SubInfoIn>
                 <sch:Header>
                    <sch:Username>slypee_mob</sch:Username>
                    <sch:Password>Slyp33M0b1423!</sch:Password>
                 </sch:Header>
                 <sch:RequestID>test-123</sch:RequestID>
                 <sch:Action>SubInfo</sch:Action>
                 <sch:MSISDN>923000854058</sch:MSISDN>
                 <sch:AttribList>
                    <!--1 or more repetitions:-->
                    <sch:AttribElement>
                       <sch:Name>ServiceName</sch:Name>
                       <sch:Value>Slypee</sch:Value>
                    </sch:AttribElement>
                 </sch:AttribList>
              </sch:SubInfoIn>
           </soapenv:Body>
        </soapenv:Envelope>



        Response: (Success scenario)

        <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
           <SOAP-ENV:Body>
              <ns0:SubInfoOut xmlns:ns0="http://www.tibco.com/schemas/Mobilink/SharedResources/Schemas/ValueAddedServices/Schema.xsd12">
                 <ns0:Status>OK</ns0:Status>
                 <ns0:Reason/>
                 <ns0:Attrib1/>
                 <ns0:Attrib2/>
                 <ns0:Attrib3/>
                 <ns0:Attrib4/>
                 <ns0:AttribList>
                    <ns0:AttribElement>
                       <ns0:Name>Vbox</ns0:Name>
                       <ns0:Value>OK</ns0:Value>
                    </ns0:AttribElement>
                 </ns0:AttribList>
              </ns0:SubInfoOut>
           </SOAP-ENV:Body>
        </SOAP-ENV:Envelope>

        Response: (Failed scenario)

        <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
           <SOAP-ENV:Body>
              <ns0:SubInfoOut xmlns:ns0="http://www.tibco.com/schemas/Mobilink/SharedResources/Schemas/ValueAddedServices/Schema.xsd12">
                 <ns0:Status>KO</ns0:Status>
                 <ns0:Reason/>
                 <ns0:Attrib1/>
                 <ns0:Attrib2/>
                 <ns0:Attrib3/>
                 <ns0:Attrib4/>
                 <ns0:AttribList>
                    <ns0:AttribElement>
                       <ns0:Name>Vbox</ns0:Name>
                       <ns0:Value>KO</ns0:Value>
                    </ns0:AttribElement>
                 </ns0:AttribList>
              </ns0:SubInfoOut>
           </SOAP-ENV:Body>
        </SOAP-ENV:Envelope>
                 */
    }

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
