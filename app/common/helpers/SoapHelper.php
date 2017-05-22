<?php

namespace common\helpers;

class SoapHelper
{
    public function GetSubInfo($obj)
    {
        //file_put_contents("log", print_r($obj, true));

        /*
        if ($obj->MSISDN == 923088290736) {
            return [
                'Status' => 'OK',
                'Reason' => '',
                'Attrib1' => null,
                'Attrib2' => null,
                'Attrib3' => null,
                'Attrib4' => null,
                'AttribList' => [
                    'AttribElement' => [
                        'Name' => 'ServiceName',
                        'Value' => 'Slypee',
                    ],
                ],
            ];
        }
        */

        return [
            'Status' => 'KO',
            'Reason' => 'Not found.',
            'Attrib1' => null,
            'Attrib2' => null,
            'Attrib3' => null,
            'Attrib4' => null,
            'AttribList' => [
                'AttribElement' => [
                    'Name' => 'ServiceName',
                    'Value' => 'Slypee',
                ],
            ],
        ];
    }
}
