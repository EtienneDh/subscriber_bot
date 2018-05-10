<?php

require 'vendor/autoload.php';

use BenderBot\Bender;

$availaibleConfigs = ['twitter'];
$config            = $availaibleConfigs[0];

if (isset($argc)) {
	for ($i = 0; $i < $argc; $i++) {
		$config = $argc[1];
	}
}

if(!in_array($config, $availaibleConfigs)) {
    exit('Unknown configuration' . "\n");
}

$bender    = new Bender();
$searchFor = ['SpaceX', 'Elon', 'Musk'];

if($bender->loadConfig($config)) {
    $bender->initClient();
    $bender->lookFor($searchFor);
}
