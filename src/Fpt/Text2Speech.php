<?php

namespace Go1\Fpt;

class Text2Speech
{
    private $apiKey;
    public  $apiEndpoint = 'http://api.openfpt.vn/text2speech/v4<dc>';

    public $verifySsl = true;

    private $requestSuccessful = false;
    private $lastError         = '';
    private $lastResponse      = [];
    private $lastRequest       = [];

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->lastResponse = ['headers' => null, 'body' => null];
    }

    public function success()
    {
        return $this->requestSuccessful;
    }

    public function getLastError()
    {
        return $this->lastError ?: false;
    }

    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    public function getLastRequest()
    {
        return $this->lastRequest;
    }

    public function post($method, $args = [], $timeout = 10)
    {
        return $this->makeRequest('post', $method, $args, $timeout);
    }

    private function makeRequest($httpVerb, $method, $args = [], $timeout = 10)
    {
        if (!function_exists('curl_init') || !function_exists('curl_setopt')) {
            throw new \Exception("cURL support is required, but can't be found.");
        }

        $url = str_replace('<dc>', $method, $this->apiEndpoint);

        $this->lastError = '';
        $this->requestSuccessful = false;
        $response = ['headers' => null, 'body' => null];
        $this->lastResponse = $response;

        $this->lastRequest = [
            'method'  => $httpVerb,
            'path'    => $method,
            'url'     => $url,
            'body'    => '',
            'timeout' => $timeout,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            [
                'Content-Type: text/plain',
                'api_key: ' . $this->apiKey,
                'voice: ' . $args['voice'],
            ]
        );
        curl_setopt($ch, CURLOPT_USERAGENT, 'GO1/Text2Speech/1.0');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->verifySsl);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);

        switch ($httpVerb) {
            case 'post':
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $args['text']);
                $this->attachRequestPayload($ch, $args);
                break;
        }

        $response['body'] = curl_exec($ch);
        $response['headers'] = curl_getinfo($ch);

        if (isset($response['headers']['request_header'])) {
            $this->lastRequest['headers'] = $response['headers']['request_header'];
        }

        if ($response['body'] === false) {
            $this->lastError = curl_error($ch);
        }

        curl_close($ch);

        return $this->formatResponse($response);
    }

    /**
     * Encode the data and attach it to the request
     *
     * @param   resource $ch   cURL session handle, used by reference
     * @param   array    $data Assoc array of data to attach
     */
    private function attachRequestPayload(&$ch, $data)
    {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }

    /**
     * Decode the response and format any error messages for debugging
     *
     * @param array $response The response from the curl request
     *
     * @return array|false     The JSON decoded into an array
     */
    private function formatResponse($response)
    {
        $this->lastResponse = $response;

        if (!empty($response['body'])) {

            $d = json_decode($response['body'], true);

            if (isset($d['status']) && $d['status'] != '200' && isset($d['detail'])) {
                $this->lastError = sprintf('%d: %s', $d['status'], $d['detail']);
            } else {
                $this->requestSuccessful = true;
            }

            return $d;
        }

        return false;
    }
}