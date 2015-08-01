<?php

namespace elmhurst\lifxConnect;

class DataPrinter
{

    private $verbosity = 0;

    public static $colours = array(
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

    public function __construct($verbosity)
    {
        $this->verbosity = $verbosity;
    }

    public static function printStr($string, $color)
    {
        echo self::$colours[$color];
        echo $string;
        echo self::$colours['white'];
    }

    public function printError($response)
    {
        echo "\nResponse Code: ".$response['http_code']."\n";
        $data = $response['data'];
        if (isset($data['error'])) {
            echo self::$colours['red'];
            echo $data['error'];
            echo self::$colours['white'];
            echo "\n";
        }
        if (isset($data['message'])) {
            echo self::$colours['red'];
            echo $data['message'];
        }
        if ($this->verbosity > 0 && isset($data['errors'])) {
            echo "\nDump: ";
            echo self::$colours['lgray'];
            echo print_r($data['errors'], true);
        }
        if ($this->verbosity > 2) {
            echo print_r($data, true);
        }
        echo self::$colours['white'];
        echo "\n";
    }

    public function printLamps($data)
    {
        $lgreen = "\033[1;32m";
        $blue   = "\033[1;34m";
        $white  = "\033[0m";
        $lpurp  = "\033[1;35m";

        if (isset($data['id'])) {
            $data = array($data);
        }
        foreach ($data as $index => $lamp) {
            echo $blue."\n  Lamp:   \t".$white.$lamp['label'];
            if ($this->verbosity > 0) {
                echo $blue."\n  Group:\t".$white.$lamp['group']['name'];
                echo $blue."\n  Location:\t".$white.$lamp['location']['name'];
                echo $blue."\n  Power:\t".$white.$lamp['power'];
            }
            if ($this->verbosity > 1) {
                echo $blue."\n  ID:   \t".$white.$lamp['id'];
                echo $blue."\n  Type: \t".$white.$lamp['product_name'];
            }
            if ($this->verbosity > 2) {
                echo "\nFull dump: ".$lpurp;
                print_r($lamp);
                echo $white;
            }
            if ($this->verbosity > 0) {
                echo "\n";
            }
        }
        echo $lgreen."\nUse -v -vv -vvv for more/less detail\n\n".$white;
    }

    public function printScenes($data)
    {
        $lgreen = "\033[1;32m";
        $blue   = "\033[1;34m";
        $white  = "\033[0m";
        $lpurp  = "\033[1;35m";
        echo "\n";
        foreach ($data as $index => $scene) {
            echo $blue."Scene $index: ".$scene['name']."\033[0m\n";
            if ($this->verbosity > 2) {
                echo "\nFull dump: ".$lpurp;
                print_r($scene);
                echo $white;
            }
        }
        echo $lgreen."\nUse -vvv for more detail.\n\n".$white;
    }

    public function printStatus($data)
    {
        $lgreen = "\033[1;32m";
        $blue   = "\033[1;34m";
        $white  = "\033[0m";
        $lpurp  = "\033[1;35m";
        if (isset($data['label'])) {
            echo $data['label']. " status: ".$data['status']."\n";
        } else {
            foreach ($data as $key => $lamp) {
                echo $blue.$lamp['label']." status: ".$white.$lamp['status']."\n";
            }
        }
        echo "\n";
    }
}
