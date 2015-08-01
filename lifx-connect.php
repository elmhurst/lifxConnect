<?php

echo "\n";

$selector = "all";
$action = "";
$method = "GET";
$responseType = "light-list";
$linkRoot = "https://api.lifx.com/v1beta1/lights/";
$verbosity = 0;

$red    = "\033[1;31m";
$green  = "\033[0;32m";
$lgreen = "\033[1;32m";
$blue   = "\033[1;34m";
$cyan   = "\033[1;36m";
$white  = "\033[0m";
$gray   = "\033[0;30m";
$lgray  = "\033[0;37m";
$lpurp  = "\033[1;35m";

// help

if (in_array("--help", $argv)) {
    echo "Help will go here!\n";
    die();
}

// verbosity
if (in_array("-vvv", $argv)) {
    $verbosity = 3;
} elseif (in_array("-vv", $argv)) {
    $verbosity = 2;
} elseif (in_array("-v", $argv)) {
    $verbosity = 1;
}

// list scenes

if (in_array("--list-scenes", $argv)) {
    $method = "GET";
    $selector = "";
    $action = "";
    $linkRoot = str_replace("lights/", "scenes", $linkRoot);
    $responseType = "scene-list";
}

// list lights

if (in_array("--list-lights", $argv)) {
    $method = "GET";
    $selector = "all";
    $action = "";
    $responseType = "light-list";
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
        $responseType = "status-list";
    }
}

// setup

$authToken = file_get_contents("token.txt");
$link = $linkRoot.$selector.$action;

$ch = curl_init($link);
curl_setopt($ch, CURLOPT_USERPWD, $authToken . ":");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
if ($method == "POST") {
    curl_setopt($ch, CURLOPT_POST, 1);
}
echo $cyan.$link.$white."\n";
$json_response = curl_exec($ch);
$response = json_decode($json_response, true);

// Error?
if (isset($response['error'])) {
    echo $red.$response['error'].$white;
    die();
}

// Data
switch ($responseType) {
    case 'status-list':
        if (isset($response['label'])) {
            echo $response['label']. " status: ".$response['status']."\n";
        } else {
            foreach ($response as $key => $lamp) {
                echo $lamp['label']. " status: ".$lamp['status']."\n";
            }
        }
        break;

    case 'scene-list':
        foreach ($response as $index => $scene) {
            echo $blue."Scene: ".$scene['name']."\033[0m\n";
            if ($verbosity > 2) {
                echo "\nFull dump: ".$lpurp;
                print_r($scene);
                echo $white;
            }
        }
        echo $lgreen."\nUse -vvv for more detail.\n".$white;
        break;

    case 'light-list':
        foreach ($response as $index => $lamp) {
            echo $blue."\n  Lamp:   \t".$white.$lamp['label'];
            if ($verbosity > 0) {
                echo $blue."\n  Group:\t".$white.$lamp['group']['name'];
                echo $blue."\n  Location:\t".$white.$lamp['location']['name'];
                echo $blue."\n  Power:\t".$white.$lamp['power'];
            }
            if ($verbosity > 1) {
                echo $blue."\n  ID:   \t".$white.$lamp['id'];
                echo $blue."\n  Type: \t".$white.$lamp['product_name'];
            }
            if ($verbosity > 2) {
                echo "\nFull dump: ".$lpurp;
                print_r($lamp);
                echo $white;
            }
            if ($verbosity > 0) {
                echo "\n";
            }
        }
        echo $lgreen."\nUse -v -vv -vvv for more/less detail\n".$white;
        break;

    default:
        print_r($response);
        break;
}
echo $white;
