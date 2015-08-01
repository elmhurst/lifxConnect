<?php
namespace elmhurst\lifxConnect;

class LIFXApiConsumer
{

    public function listLamps($requestOptions)
    {
        $callOptions = array(
            "method" => "GET",
            "data"   => null,
            "path"   => 'lights/'.$requestOptions['selector']
        );
        $request = new ApiCall($callOptions);
        $data = $request->doCall();
        return $data;
    }

    public function listScenes($requestOptions)
    {
        $callOptions = array(
            "method" => "GET",
            "data"   => null,
            "path"   => 'scenes'
        );
        $request = new ApiCall($callOptions);
        $data = $request->doCall();
        return $data;
    }

    public function togglePower($requestOptions)
    {
        $callOptions = array(
            "method" => "POST",
            "data"   => null,
            "path"   => 'lights/'.$requestOptions['selector'].'/toggle'
        );
        $request = new ApiCall($callOptions);
        $data = $request->doCall();
        return $data;
    }

    public function setPower($requestOptions)
    {
        $callOptions = array(
            "method" => "PUT",
            "data"   => $requestOptions['data'],
            "path"   => 'lights/'.$requestOptions['selector'].'/power'
        );
        $request = new ApiCall($callOptions);
        $data = $request->doCall();
        return $data;
    }

    public function setColor($requestOptions)
    {
        $callOptions = array(
            "method" => "PUT",
            "data"   => $requestOptions['data'],
            "path"   => 'lights/'.$requestOptions['selector'].'/color'
        );
        $request = new ApiCall($callOptions);
        $data = $request->doCall();
        return $data;
    }

    public function activateScene($requestOptions)
    {
        $callOptions = array(
            "method" => "PUT",
            "data"   => $requestOptions['data'],
            "path"   => 'scenes/scene_id:'.$requestOptions['selector'].'/activate'
        );
        $request = new ApiCall($callOptions);
        $data = $request->doCall();
        return $data;
    }

    public function effect($effectName, $requestOptions)
    {

    }

    private function parseData($string, $defaults)
    {

    }
}
