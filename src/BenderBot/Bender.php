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
        $retour        = json_decode($this->results->getBody(), true);
        $tweets        = $retour['statuses'];

        $tweetModel   = $this->mp->getModel('tweet');
        $accountModel = $this->mp->getModel('account');

         foreach($tweets as $tweet) {

            if(!$tweetModel->isTweetAlreadyRT($tweet['id_str'])){

                /* Add tweet */
                $t = $tweetModel->getBeanForInsert();

                /* hydrate the bean */
                $t->idTweet = $tweet['id_str'];
                $t->text    = $tweet['full_text'];
                $t->rt      = $tweet['retweeted'];
                $t->dateAdd = date("Y-m-d H:i:s");
                /* Set meta for long ID */
                $t->setMeta('cast.idTweet','text');
                /* Save it */
                $tweetModel->save($t);

                /* Add account */
                $a = $accountModel->getBeanForInsert();
                // hydrate the bean
                $a->idTwitter      = $tweet['user']['id_str'];
                $a->name           = $tweet['user']['name'];
                $a->following      = $tweet['user']['following'];
                $a->ownTweetList[] = $t;
                $a->dateAdd        = date("Y-m-d H:i:s");
                /* Set meta for long ID */
                $a->setMeta('cast.idTwitter','text');
                /* Save it */
                $accountModel->save($a);

            } else {
                echo "tweet already RT: ".  $tweet['id_str'] ."\n";
            }
        }

        echo 'Tweet : ' . $tweetModel->count() . ' <br /> ';
        echo 'Account : ' . $accountModel->count() . '<br />' ;

    }
}
