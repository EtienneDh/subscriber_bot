<?php

namespace BenderBot;

use \RedBeanPHP\R as R;
use BenderBot\AbstractBender;
// use BenderBot\Entity\Account;

class Bender extends AbstractBender
{
    private $results;

    public function run()
    {
        // Look for tweet
        $this->results = $this->api->search();
        // if matching:
            // subscribe
            // repost
            // Save to db
            // chill

        //$retour = $this->results->getBody();
        $retour = json_decode($this->results->getBody(), true);
        //
        $tweets = $retour['statuses'];
        //
        // "Echoing authors and text ... \n";
        // foreach($tweets as $tweet) {
        //     echo $tweet['user']['name'] . "\n";
        //     echo $tweet['text'] . "\n";
        //     echo "-------------------\n";
        // }

        // $account = R::load( 'account', 1 );
        // var_dump($account);


        "Echoing authors and text ... <br/>\n";
        foreach($tweets as $tweet) {
            echo $tweet['user']['name'] . ' - '. $tweet['id'] . "<br/>\n";
            echo $tweet['full_text'] . "<br/>\n";
            echo "-------------------<br/>\n";
        }
        exit;

    }
}
