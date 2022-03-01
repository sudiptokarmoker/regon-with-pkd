<?php

namespace App\Services\Common;

ini_set("soap.wsdl_cache_enabled", WSDL_CACHE_NONE);
use Illuminate\Support\Facades\Log;

abstract class SoapService
{
    protected $client;
    protected const ADDRESSING_URI = "http://www.w3.org/2005/08/addressing";

    public function __construct()
    {
        $this->client = $this->getClient();
    }

    /**
     * Provide specific client
     * base on the type of
     * Soap Service
     *
     * @param string|null $session_id
     * @return SoapClient
     */
    public abstract function getClient(string $session_id = null);

    public function provider($method, $query, $action)
    {
        $params = $this->getSoapParams($query);
        try {
            $headers = [
                new \SoapHeader(self::ADDRESSING_URI, 'Action', $action),
                new \SoapHeader(self::ADDRESSING_URI, 'To', config('regon.api.base_url')),
            ];
            $this->client->__setSoapHeaders($headers);
            $response = $this->client->__soapCall($method, $params, [
                'uri' => config('regon.api.uri'),
                'location' => config('regon.api.base_url'),
                'soapaction' => $action
            ]);

        } catch (\SoapFault $e) {
            Log::info('Last failed request response: ' . $this->client->__getLastRequest());
            throw new \Exception('Problem with SOAP call: ' . $e->getMessage());
        }
        return $response;
    }

    /**
     * Format parameters
     * for SOAP request
     * fro given query
     * parameter
     *
     * @param array|SoapVar $query
     *
     * @return SoapVar[]|array
     */
    public function getSoapParams($query)
    {
        if (is_array($query)) {
            $params = [];
            foreach ($query as $key => $value) {
                $params[] = new \SoapParam($value, 'ns1:' . $key);
            }
            return $params;
        }

        if ($query instanceof \SoapVar) {
            return [$query];
        }

        return [];
    }
}
