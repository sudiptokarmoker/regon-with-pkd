<?php

namespace App\Services;

use App\Services\Common\ExtendedSoapClient;
use App\Services\Common\SoapService;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;
use SoapClient;

class RegonService extends SoapService
{
    protected $base_url;
    protected $uri;
    protected $key;
    protected $client;
    protected $session_id;

    protected const TRACE = 1;
    protected const SOAP_1_2 = 2;
    protected const SOAP_RPC = 1;
    protected const SOAP_ENCODED = 1;
    protected const WSDL_CACHE_NONE = 0;
    protected const ENABLE_EXCEPTIONS = true;
    protected const CONNECTION_TIMEOUT = 25;
    protected const SOAP_ENC_OBJECT = 301;
    protected const XML_SOAP_ENCODING = "http://www.w3.org/2003/05/soap-encoding";

    public function __construct()
    {
        $this->base_url = config('regon.api.base_url');
        $this->uri = config('regon.api.uri');
        $this->key = config('regon.api.key');

        $this->client = $this->getClient();
        $this->session_id = $this->getSessionId($this->key);
    }

    public function getClient(string $session_id = null): SoapClient
    {
        $header = $session_id ? 'sid:' . $session_id : '';
        $options = [
            'location'      => $this->base_url,
            'uri'           => $this->uri,
            'soap_version'  => self::SOAP_1_2,
            'use'           => self::SOAP_ENCODED,
            'trace'         => self::TRACE,
            'exceptions'    => self::ENABLE_EXCEPTIONS,
            'cache_wsdl'    => self::WSDL_CACHE_NONE,
            'style'         => self::SOAP_RPC,
            'connection_timeout' => self::CONNECTION_TIMEOUT,
            'stream_context' => stream_context_create(array(
                'http' => array(
                    'header' => $header,
                ),
            )),
        ];
        return new ExtendedSoapClient(null, $options);
    }

    /**
     * Get a session id
     * given the User key
     *
     * @param string $user_key
     * @return string|null
     */
    public function getSessionId($user_key)
    {
        $method = 'Zaloguj';
        $params = ['pKluczUzytkownika' => $user_key];
        $action = self::getActionFromMethod($method);

        return $this->provider($method, $params, $action);
    }

    /**
     * Search records in the regon database
     * base on given paramters
     * nip, regon ...
     *
     * @param array $params
     * @return array|null
     */
    public function searchRecord(array $params)
    {
        if (!$this->sessionIdIsValid()) {
            return null;
        } else {
            $this->refreshClientInstance();
        }

        $method = 'DaneSzukajPodmioty';
        $action = self::getActionFromMethod($method);
        $nip = $params['nip'];

        $nip = new \SoapVar($nip, XSD_STRING, null, null, 'Nip', config('regon.api.uri') . '/DataContract');
        $search_param = new \SoapVar(
            array($nip), SOAP_ENC_OBJECT, '', self::XML_SOAP_ENCODING, 'pParametryWyszukiwania', config('regon.api.uri'));

        try {
            $response = $this->provider($method, $search_param, $action);
            $xml = new SimpleXMLElement($response);
            $error = $xml->xpath("//root/dane/ErrorCode");
            if ($error) {
                return null;
            } else {
                return $this->parseSearchResult($xml);
            }
        } catch (\Throwable $th) {
            Log::error('Error in regon service searching function:' . $th);
            return null;
        }
    }

    public function parseSearchResult(SimpleXMLElement $response): array
    {
       $result = [
            'nazwa' => (string) $response->xpath("//root/dane/Nazwa")[0],
            'regon' => (string) $response->xpath("//root/dane/Regon")[0],
            'nip' => (string) $response->xpath("//root/dane/Nip")[0],
            'wojewodztwo' => (string) $response->xpath("//root/dane/Wojewodztwo")[0],
            'powiat' => (string) $response->xpath("//root/dane/Powiat")[0],
            'gmina' => (string) $response->xpath("//root/dane/Gmina")[0],
            'miejscowosc' => (string) $response->xpath("//root/dane/Miejscowosc")[0],
            'kodPocztowy' => (string) $response->xpath("//root/dane/KodPocztowy")[0],
            'ulica' => (string) $response->xpath("//root/dane/Ulica")[0],
            'typ' => (string) $response->xpath("//root/dane/Typ")[0],
            'silosID' => (string) $response->xpath("//root/dane/SilosID")[0],
            'name' => (string) $response->xpath("//root/dane/Nazwa")[0],
        ];
        $result['address'] = $result['wojewodztwo'] . $result['powiat']
            . $result['gmina'] . $result['miejscowosc']
            . $result['kodPocztowy'] . $result['ulica'];

        return $result;
    }

    public function getCompanyPKDList(string $regon, string $silosID)
    {
        $method = 'DanePobierzPelnyRaport';
        $action = self::getActionFromMethod($method);
        $params = [
            "pRegon" => str_pad($regon, 14, 0),
            "pNazwaRaportu" => "DaneRaportDzialalnosciFizycznejPubl",
            "pSilosID" => $silosID
        ];

        try {
            $response = $this->provider($method, $params, $action);
            $xml = new SimpleXMLElement($response);
            $error = $xml->xpath("//root/dane/ErrorCode");

            if ($error) {
                return null;
            } else {
                return $this->parseCompanyListRequestResponse($xml);
            }
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Error in regon service searching function:' . $th);
            return null;
        }
    }

    public function parseCompanyListRequestResponse(SimpleXMLElement $response): array
    {
        $pkds = $response->xpath("//root/dane");
        $result = [];
        foreach ($pkds as $pkd) {
            $result[] = (string) $pkd->xpath("fiz_pkdKod")[0];
        }

        return $result;
    }

    /**
     * Recreate SoapClient
     * with the new session_id
     *
     * @return bool
     */
    public function refreshClientInstance()
    {
        $this->client = $this->getClient($this->session_id);
    }

    /**
     * Check if session_id is valid
     *
     * @return bool
     */
    public function sessionIdIsValid()
    {
        return $this->session_id && !empty($this->session_id);
    }

    public static function getActionFromMethod(string $method)
    {
        return config('regon.api.uri') . '/' . 'IUslugaBIRzewnPubl' . '/' . $method;
    }
}
