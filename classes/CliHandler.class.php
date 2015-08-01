<?
namespace elmhurst\lifxConnect;

class CliHandler {

    public $verbosity;
    public $colours = array[
        'red'    => "\033[1;31m";
        'green'  => "\033[0;32m";
        'lgreen' => "\033[1;32m";
        'blue'   => "\033[1;34m";
        'cyan'   => "\033[1;36m";
        'white'  => "\033[0m";
        'gray'   => "\033[0;30m";
        'lgray'  => "\033[0;37m";
        'purp'   => "\033[0;35m";
        'lpurp'  => "\033[1;35m";
    ]

    public function parseArgs()
    {
        // help
        if (in_array("--help", $argv)) {
            $this->say("Help will go here!",'red',true);
            return 'help';
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
        if (in_array("--list-scenes", $argv)) {
            $method = "GET";
            $selector = "";
            $action = "";
            $linkRoot = str_replace("lights/", "scenes", $linkRoot);
            $responseType = "Scene List";
        }

        // list lights
        if (in_array("--list-lights", $argv)) {
            $method = "GET";
            $selector = "all";
            $action = "";
            $responseType = "Light List";
        }

        // selector
        $selectorArg = array_search("--selector", $argv, true);
        if ($selectorArg) {
            $i = $selectorArg + 1;
            $selector = $argv[$i];
            $i++;
            while ($i < count($argv) && substr($argv[$i], 0, 1) != '-') {
                $selector .= " ".$argv[$i];
                $i++;
            }
        }

        // action
        $actionArg = array_search("--action", $argv, true);
        if ($actionArg) {
            $i = $actionArg + 1;
            if (strlen($argv[$i]) > 0) {
                $action = "/".$argv[$i];
                $method = "POST";
                $responseType = "Status List";
            }
        }
    }

    public function say($string, $color = 'white', $newline = true)
    {
        echo $this->colours[$color];
        echo $string;
        echo ($newline)?"\n":"";
    }


}