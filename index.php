<?php

require 'vendor/autoload.php';

use BenderBot\Bender;


// $res = $client->get('statuses/home_timeline.json');
//
// $kittens = $client->get('search/tweets.json', [
//     'query' => [
//         'q' => '@kittens',
//         'result_type' => 'mixed'
//     ]
// ]);
//
// echo $kittens->getBody();
// exit;


$bender    = new Bender();
$searchFor = ['SpaceX', 'Elon', 'Musk'];

if($bender->loadConfig('twitter')) {
    $bender->initClient();
    $bender->lookFor($searchFor);
}
