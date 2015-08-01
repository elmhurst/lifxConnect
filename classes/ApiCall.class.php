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

    public function __construct($options = array())
    {
        $this->options = array_merge($options, $this->options);
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
        if (is_array($this->options['data'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->options['data']));
        }

        // execute and process
        $json_response = curl_exec($ch);
        $response = json_decode($json_response, true);

        return $response;
    }
}
