<?php

namespace elmhurst\lifxConnect;

class CliHandler
{

    private $verbosity = 0;
    private $selector = '';
    private $action = '';
    private $effect = '';
    private $dataString;
    private $cliArgs;
    private $printer;
    private $sceneid;

    public function __construct($argv, $start = true)
    {
        $this->cliArgs = $argv;
        if ($start) {
            $this->start();
        }
    }

    public function start()
    {
        $this->parseArgs();
        $this->printer = new DataPrinter($this->verbosity);
        $this->callApi();
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getSelector()
    {
        return $this->selector;
    }

    public function getVerbosity()
    {
        return $this->verbosity;
    }

    public function callApi()
    {
        $lifx = new LIFXApiConsumer();
        $requestOptions = array(
            'selector'  => $this->selector,
            'data' => $this->dataString,
            'effect'    => $this->effect
        );
        switch (strtolower($this->action)) {
            case 'help':
                $this->printer->printHelp();
                break;

            case 'list lights':
            case 'list lamps':
                $response = $lifx->listLamps($requestOptions);
                if ($this->notError($response)) {
                    $this->printer->printLamps($response['data']);
                }
                break;

            case 'list scenes':
                $response = $lifx->listScenes($requestOptions);
                if ($this->notError($response)) {
                    $this->printer->printScenes($response['data']);
                }
                break;

            case 'toggle':
                $response = $lifx->togglePower($requestOptions);
                if ($this->notError($response)) {
                    $this->printer->printStatus($response['data']);
                }
                break;

            case 'set power':
                $response = $lifx->setPower($requestOptions);
                if ($this->notError($response)) {
                    $this->printer->printStatus($response['data']);
                }
                break;

            case 'set color':
                $response = $lifx->setColor($requestOptions);
                if ($this->notError($response)) {
                    $this->printer->printStatus($response['data']);
                }
                break;

            case 'activate scene':
                $response = $lifx->activateScene($requestOptions);
                if ($this->notError($response)) {
                    $this->printer->printStatus($response['data']);
                }
                break;

            case 'choose scene':
                $response = $lifx->listScenes($requestOptions);
                if ($this->notError($response)) {
                    $this->printer->printScenes($response['data']);
                    $this->getSceneUuid($response['data'], $lifx, true);
                }
                break;

            case 'scene number':
                $response = $lifx->listScenes($requestOptions);
                if ($this->notError($response)) {
                    $this->getSceneUuid($response['data'], $lifx, false);
                }
                break;

            case 'effect':
                $response = $lifx->effect($requestOptions);
                if ($this->notError($response)) {
                    $this->printer->printStatus($response['data']);
                }
                break;

            default:
                break;
        }
    }

    private function parseArgs()
    {
        // help
        if ($this->matchArg('help', 'h')) {
            $this->action = 'Help';
        }

        // verbosity
        if (in_array("-vvv", $this->cliArgs)) {
            $this->verbosity = 3;
        } elseif (in_array("-vv", $this->cliArgs)) {
            $this->verbosity = 2;
        } elseif (in_array("-v", $this->cliArgs)) {
            $this->verbosity = 1;
        } else {
            $this->verbosity = 0;
        }

        // list scenes
        if ($this->matchArg('list-scenes', 'ls')) {
            $this->action = 'List Scenes';
            return true;
        }

        // list lights
        if ($this->matchArg('list-lamps', 'll')) {
            $this->action = 'List Lamps';
        }

        // choose a scene
        if ($this->matchArg('choose-scene', 'cs')) {
            $this->action = 'choose scene';
        }

        // choose a scene by number
        $scenenumberArgPos = $this->findArg('scene-number', 'sn');
        if ($scenenumberArgPos) {
            $this->action = 'scene number';
            $this->sceneid =  $this->cliArgs[$scenenumberArgPos+1];
        }

        // choose an effect
        $scenenumberArgPos = $this->findArg('effect', 'e');
        if ($scenenumberArgPos) {
            $this->action = 'effect';
            $this->effect =  $this->cliArgs[$scenenumberArgPos+1];
        }

        // selector
        $selectorArgPos = $this->findArg('selector', 's');
        if ($selectorArgPos) {
            $i = $selectorArgPos + 1;
            $selector = $this->cliArgs[$i];
            $i++;
            while ($i < count($this->cliArgs) && substr($this->cliArgs[$i], 0, 1) != '-') {
                $selector .= " ".$this->cliArgs[$i];
                $i++;
            }
            $this->selector = $selector;
        }

        // action
        $actionArgPos = $this->findArg('action', 'a');
        if ($actionArgPos) {
            $i = $actionArgPos + 1;
            $action = $this->cliArgs[$i];
            $i++;
            while ($i < count($this->cliArgs) && substr($this->cliArgs[$i], 0, 1) != '-') {
                $action .= " ".$this->cliArgs[$i];
                $i++;
            }
            $this->action = $action;
        }

        // action
        $dataArgPos = $this->findArg('data', 'd');
        if ($dataArgPos) {
            $i = $dataArgPos + 1;
            $data = $this->cliArgs[$i];
            $i++;
            while ($i < count($this->cliArgs) && substr($this->cliArgs[$i], 0, 1) != '-') {
                $data .= "&".$this->cliArgs[$i];
                $i++;
            }
            $this->dataString = $data;
        }

        return true;
    }

    private function getSceneUuid($data, $lifx, $ask = 'false')
    {
        if ($ask) {
            echo "Enter the number of the scene you would like to activate: ";
            $handle = fopen('php://stdin', 'r');
            $line = rtrim(fgets($handle), "\r\n");
            if (!isset($data[$line])) {
                echo "I couldn't find that scene, try another: ";
                $handle = fopen('php://stdin', 'r');
                $line = rtrim(fgets($handle), "\r\n");
            }
            if (!isset($data[$line])) {
                echo "Still no good. Maybe next time?\n\n";
                return false;
            }
            $sceneUuid = $data[$line]['uuid'];
        } else {
            $sceneUuid = $data[$this->sceneid]['uuid'];
        }
        $requestOptions = array(
            'selector'  => $sceneUuid,
            'data'      => "",
            'effect'    => $this->effect
        );
        $response = $lifx->activateScene($requestOptions);
        if ($this->notError($response)) {
            $this->printer->printStatus($response['data']);
        }
    }

    private function notError($response)
    {
        if (isset($response['data']['error'])) {
            $this->printer->printError($response);
            return false;
        }
        if ($response['http_code'] < 200 || $response['http_code'] > 300) {
            $this->printer->printError($response);
            return false;
        }
        return true;
    }

    private function matchArg($long, $short)
    {
        return (in_array("--".$long, $this->cliArgs) || in_array("-".$short, $this->cliArgs));
    }

    private function findArg($long, $short)
    {
        $argPos = array_search('--'.$long, $this->cliArgs, true);
        if ($argPos) {
            return $argPos;
        }
        return array_search('-'.$short, $this->cliArgs, true);
    }
}
