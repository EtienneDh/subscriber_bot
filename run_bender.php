<?php

require 'vendor/autoload.php';

use Utils\Factory;
use Utils\Tools;
use \RedBeanPHP\R as R;

const PARAMETERS_FILE  = 'parameters';

$availaibleAppNames = ['twitter'];

// Check if app name is passed as script params
$appName = isset($argv[1]) ? $argv[1] : $availaibleAppNames[0];

if(!in_array($appName, $availaibleAppNames)) {
    exit('Unknown application name' . "\n");
}

// RedBean ORM init
$params   = Tools::extractJsonFromFile(PARAMETERS_FILE);
$host     = $params['database']['host'];
$dbname   = $params['database']['name'];
$user     = $params['database']['user'];
$password = $params['database']['password'];

R::setup( "mysql:host=localhost;dbname=benderbot_rb", $user, $password);

$benderBot = Factory::getBender($appName);
$benderBot->run();
