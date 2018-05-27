<?php

namespace BenderBot;

use BenderBot\AbstractBender;
use BenderBot\Entity\Account;

class Bender extends AbstractBender
{
    private $results;

    public function run()
    {
        // Look for tweet
        // $this->results = $this->api->search();
        // if matching:
            // subscribe
            // repost
            // Save to db
            // chill

        //$retour = $this->results->getBody();
        // $retour = json_decode($this->results->getBody(), true);
        //
        // $tweets = $retour['statuses'];
        //
        // "Echoing authors and text ... \n";
        // foreach($tweets as $tweet) {
        //     echo $tweet['user']['name'] . "\n";
        //     echo $tweet['text'] . "\n";
        //     echo "-------------------\n";
        // }

        $a = new Account();

        exit(var_dump($a::getEntityName()));

        $a->idTwitter = 12353;
        $a->name = 'test';
        $a->dateAdd = new \DateTime('now');

        $a::save();

        // $account = R::load( 'account', 1 );
        // var_dump($account);

        exit;

    }
}
