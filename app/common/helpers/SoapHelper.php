<?php

namespace common\helpers;


class SoapHelper
{
    public function GetSubInfo($obj)
    {
        return [
            'Status' => 'OK',
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
