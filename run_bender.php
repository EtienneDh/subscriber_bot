<?php

require 'vendor/autoload.php';

use Utils\Factory;
use \RedBeanPHP\R as R;

R::setup('mysql:host=localhost;dbname=benderbot_rb', 'root', '');

$availaibleAppNames = ['twitter'];

// Check if config is passed as script params
$appName = isset($argv[1]) ? $argv[1] : $availaibleAppNames[0];

if(!in_array($appName, $availaibleAppNames)) {
    exit('Unknown application name' . "\n");
}

// move to config or argv
$query   = ['Kerbal Space Program'];
$options = [];

$benderBot = Factory::getBender($appName);
$benderBot->setQuery($query, $options);
$benderBot->run();
