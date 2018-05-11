<?php

require 'vendor/autoload.php';

use BenderBot\Bender;

$availaibleConfigs = ['twitter'];

// Check if config is passed as script paramo
$config = isset($argv[1]) ? $argv[1] : $availaibleConfigs[0];

if(!in_array($config, $availaibleConfigs)) {
    exit('Unknown configuration' . "\n");
}

$bender    = new Bender();
$searchFor = ['SpaceX', 'Elon', 'Musk'];

if($bender->loadConfig($config)) {
    $bender->initClient();
    $bender->lookFor($searchFor);
}
