<?php

namespace App\Services\Common;

use Illuminate\Support\Facades\Log;
use SoapClient;

class ExtendedSoapClient extends SoapClient
{
    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        $request = str_replace('xsi:type="enc:Struct"', '', $request);
        $result = parent::__doRequest($request, $location, $action, $version, $one_way);
        $headers = $this->__getLastResponseHeaders();
        if (preg_match('#^Content-Type:.*multipart\/.*#mi', $headers) !== 0) {
            $result = str_replace("\r\n", "\n", $result);
            list(, $content) = preg_split("#\n\n#", $result);
            list($result, ) = preg_split("#\n--#", $content);
        }
        return $result;
    }
}
