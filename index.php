<?php

require 'vendor/autoload.php';

use BenderBot\Bender;

$availaibleConfigs = ['twitter'];

// Check if config is passed as script params
$config = isset($argv[1]) ? $argv[1] : $availaibleConfigs[0];

if(!in_array($config, $availaibleConfigs)) {
    exit('Unknown configuration' . "\n");
}

$bender    = new Bender();
$searchFor = ['Witcher'];

if($bender->loadConfig($config)) {
    $bender->initClient();
    $bender->searchFor($searchFor);
}
