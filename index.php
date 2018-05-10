<?php

require 'vendor/autoload.php';

use BenderBot\Bender;

$bender    = new Bender();
$searchFor = ['SpaceX', 'Elon', 'Musk'];

if($bender->loadConfig('twitter')) {
    $bender->initClient();
    $bender->lookFor($searchFor);
}
