<?php

namespace BenderBot;

use BenderBot\AbstractBender;

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
         foreach($tweets as $tweet) {

            // Get Model from ModelProvider
            $tweetModel = $this->mp->getModel('tweet');

            // Get bean from model. This is due to RedBean behaviour where you get
            // your entity from RedBean rather than passing it to the ORM.
            // Mandatory for create only.
            $t = $tweetModel->getBeanForInsert();

            // hydrate the bean
            $t->tweet = $tweet['id_str'];
            $t->text = $tweet['full_text'];
            $t->rt = $tweet['retweeted'];
            $t->dateAdd = date("Y-m-d H:i:s");

            // save it
            $tweetModel->save($t);

            // Get Model from ModelProvider
            $accountModel = $this->mp->getModel('account');

            // Get bean from model. This is due to RedBean behaviour where you get
            // your entity from RedBean rather than passing it to the ORM.
            // Mandatory for create only.
            $a = $accountModel->getBeanForInsert();

            // hydrate the bean
            $a->user= $tweet['user']['id_str'];
            $a->name = $tweet['user']['name'];
            $a->following = $tweet['user']['following'];
            $a->ownTweetList[] = $t;
            $a->dateAdd = date("Y-m-d H:i:s");

            //$accountModel->setMeta('cast.user','string'); 
            // save it
            $accountModel->save($a);
        }
        // exit(var_dump($tweet['user']));

        

        echo "saved \n";

        echo "loading .. \n";


    }
}
