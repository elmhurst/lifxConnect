<?php

namespace elmhurst\lifxConnect;

class ApiCall
{
    private $basePath = "https://api.lifx.com/v1beta1/";
    private $options = array(
        'method' => 'GET',
        'data' => null,
        'path' => 'lights/all'
    );
    private $tokenPath = "token.txt";
    private $responseCode;

    public function __construct($options = array())
    {
        $this->options = array_merge($this->options, $options);
    }

    public function setBasePath($url)
    {
        $this->basePath = $url;
    }

    public function setOptions($options)
    {
        $this->options = array_merge($options, $this->options);
    }

    public function doCall()
    {

        $authToken = file_get_contents($this->tokenPath);
        $URL = $this->basePath.$this->options['path'];

        DataPrinter::printStr($URL, 'cyan');

        // initialise and authenticate
        $ch = curl_init($URL);
        curl_setopt($ch, CURLOPT_USERPWD, $authToken . ":");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


        // set the HTTP request method
        switch ($this->options['method']) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, 1);
                break;

            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                break;

            default:
                break;
        }

        // add the data
        if (isset($this->options['data'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->options['data']);
        }

        // execute and process
        $json_response = curl_exec($ch);
        $data = json_decode($json_response, true);

        $this->responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        echo " ".$this->responseCode."\n";

        $response = array(
            'http_code' => $this->responseCode,
            'data' => $data
        );

        return $response;
    }
}
