<?php

namespace elmhurst\lifxConnect;

class CliHandler
{

    public $colours = array(
        'red'    => "\033[1;31m",
        'green'  => "\033[0;32m",
        'lgreen' => "\033[1;32m",
        'blue'   => "\033[1;34m",
        'cyan'   => "\033[1;36m",
        'white'  => "\033[0m",
        'gray'   => "\033[0;30m",
        'lgray'  => "\033[0;37m",
        'purp'   => "\033[0;35m",
        'lpurp'  => "\033[1;35m"
    );
    private $verbosity;
    private $selector;
    private $action;

    public function __construct($start = true)
    {
        if ($start) {
            $this->start();
        }
    }

    public function start()
    {
        return $this->parseArgs;
    }

    public function say($string, $color = 'white', $newline = true)
    {
        echo $this->colours[$color];
        echo $string;
        echo ($newline)?"\n":"";
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

    private function parseArgs()
    {
        // help
        if ($this->matchArg('help', 'h')) {
            $this->say("Help will go here!", 'red', true);
            $this->action = 'Help';
        }

        // verbosity
        if (in_array("-vvv", $argv)) {
            $this->verbosity = 3;
        } elseif (in_array("-vv", $argv)) {
            $this->verbosity = 2;
        } elseif (in_array("-v", $argv)) {
            $this->verbosity = 1;
        }

        // list scenes
        if ($this->matchArg('list-scenes', 'ls')) {
            $this->action = 'List Scenes';
            return true;
        }

        // list lights
        if (matchArg('list-lamps', 'll')) {
            $this->action = 'List Lamps';
        }

        // selector
        $selectorArgPos = $this->findArg('selector', 's');
        if ($selectorArgPos) {
            $i = $selectorArgPos + 1;
            $selector = $argv[$i];
            $i++;
            while ($i < count($argv) && substr($argv[$i], 0, 1) != '-') {
                $selector .= " ".$argv[$i];
                $i++;
            }
            $this->selector = $selector;
        }

        // action
        $actionArgPos = $this->findArg('action', 'a');
        if ($actionArgPos) {
            $this->action = $argv[$actionArgPos];
        }

        return true;
    }

    private function matchArg($long, $short)
    {
        return (in_array("--".$long, $argv) || in_array("-".$short, $argv));
    }

    private function findArg($long, $short)
    {
        $ArgPos = array_search('--'.$long, $argv, true);
        if ($argPos) {
            return $argPos;
        }
        return array_search('-'.$short, $argv, true);
    }
}
