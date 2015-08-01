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
            'queryData' => $this->dataString,
            'effect'    => $this->effect
        );
        switch (strtolower($this->action)) {
            case 'list lights':
            case 'list lamps':
                $data = $lifx->listLamps($requestOptions);
                $this->printer->printLamps($data);
                break;

            case 'list scenes':
                $data = $lifx->listScenes($requestOptions);
                $this->printer->printScenes($data);
                break;

            case 'toggle':
                $data = $lifx->togglePower($requestOptions);
                $this->printer->printStatus($data);
                break;

            case 'set power':
                $data = $lifx->setPower($requestOptions);
                $this->printer->printStatus($data);
                break;

            case 'set color':
                $data = $lifx->setColor($requestOptions);
                $this->printer->printStatus($data);
                break;

            case 'activate scene':
                $data = $lifx->activateScene($requestOptions);
                $this->printer->printStatus($data);
                break;

            case 'effect':
                $data = $lifx->effect($requestOptions);
                $this->printer->printStatus($data);
                break;

            default:
                break;
        }
    }

    private function parseArgs()
    {
        // help
        if ($this->matchArg('help', 'h')) {
            $this->say("Help will go here!", 'red', true);
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
            $this->data = $data;
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
