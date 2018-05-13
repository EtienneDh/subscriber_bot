<?php

require 'vendor/autoload.php';

use Utils\Factory;

$availaibleAppNames = ['twitter'];

// Check if config is passed as script params
$appName = isset($argv[1]) ? $argv[1] : $availaibleAppNames[0];

if(!in_array($appName, $availaibleAppNames)) {
    exit('Unknown application name' . "\n");
}

$benderBot = Factory::getBender($appName);
