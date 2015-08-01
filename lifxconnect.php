<?php

namespace elmhurst\lifxConnect;

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once "classes/ApiCall.class.php";
require_once "classes/CliHandler.class.php";
require_once "classes/LIFXApiConsumer.class.php";
require_once "classes/DataPrinter.class.php";

$cliHandler = new CliHandler($argv);
