<?php

namespace elmhurst\lifxConnect;

class DataPrinter
{

    private $verbosity = 0;

    public function __construct($verbosity)
    {
        $this->verbosity = $verbosity;
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
}
