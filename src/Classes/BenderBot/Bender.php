<?php

namespace BenderBot;

use BenderBot\AbstractBender;
// use BenderBot\Entity\Account;

class Bender extends AbstractBender
{
    private $results;

    public function run()
    {
        // Look for tweets
        $this->results = $this->api->search($this->query, $this->options);
        // if matching:
            // subscribe
            // repost
            // Save to db
            // chill

        //$retour = $this->results->getBody();
        $retour = json_decode($this->results->getBody(), true);


        //exit(var_dump($retour));
        $tweets = $retour['statuses'];

        "Echoing authors and text ... <br/>\n";
        foreach($tweets as $tweet) {
            echo $tweet['user']['name'] . "<br/>\n";
            echo $tweet['full_text'] . "<br/>\n";
            echo "-------------------<br/>\n";
        }
        exit;

    }
}
