<?php

$selector = "all";
$action = "/toggle";
$method = "GET";

// help

if (in_array("--help", $argv)) {
    echo "Help will go here!\n";
    die();
}

// list lights

if (in_array("--list-lights", $argv)) {
    echo "Help will go here!\n";
    die();
}

// selector

$selectorArg = in_array("--selector", $argv);
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
$actionArg = in_array("--action", $argv);
if ($actionArg) {
    $i = $actionArg + 1;
    $action = "/".$argv[$i];
}

// setup

$authToken = file_get_contents("token.txt");
$link = "https://api.lifx.com/v1beta1/lights/$selector$action";

$ch = curl_init($link);
curl_setopt($ch, CURLOPT_USERPWD, $authToken . ":");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
if ($action = "POST") {
    curl_setopt($ch, CURLOPT_POST, 1);
}
echo "\n".$link."\n";
$response = curl_exec($ch);
$json_response = json_decode($response, true);
var_dump($json_response);
