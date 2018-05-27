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

        // exit(var_dump($a::getEntityName()));
        $this->results = $this->api->search();
        $retour = json_decode($this->results->getBody(), true);

        $tweets = $retour['statuses'];
        $tweet  = $tweets[1];
        // exit(var_dump($tweet['user']));

        // Get Model from ModelProvider
        $accountModel = $this->mp->getModel('account');

        // Get bean from model. This is due to RedBean behaviour where you get
        // your entity from RedBean rather than passing it to the ORM.
        // Mandatory for create only.
        $a = $accountModel->getBeanForInsert(); // todo: remove arg & return bean according to child class.

        // hydrate the bean
        $a->idTwitter = $tweet['user']['id'];
        $a->name = $tweet['user']['name'];
        $a->dateAdd = date("Y-m-d H:i:s");

        // save it
        // $accountModel->save($a);

        echo "saved \n";

        echo "loading .. \n";

        // load by Id
        $account = $accountModel->load(1);
        var_dump($account);

        exit;

    }
}
