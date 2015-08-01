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
        $request = new ApiCall();
        $data = $request->doCall();
        return $data;
    }

    public function listScenes()
    {

    }

    public function togglePower()
    {

    }

    public function setPower()
    {

    }

    public function setColor()
    {

    }

    public function activateScene()
    {

    }

    public function effect($effectName)
    {

    }

    public function doCall()
    {

    }
}
