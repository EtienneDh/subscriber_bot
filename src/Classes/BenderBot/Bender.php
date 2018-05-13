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
    }
}
