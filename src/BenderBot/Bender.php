<?php

namespace BenderBot;

use BenderBot\AbstractBender;
use RedBeanPHP\OODBBean;

class Bender extends AbstractBender
{
    private $results;

    public function run()
    {
        $tweetModel   = $this->mp->getModel('tweet');
        $accountModel = $this->mp->getModel('account');

        // Look for tweet
        $this->results = $this->api->search();
        $retour        = json_decode($this->results->getBody(), true);
        $tweets        = $retour['statuses'];

        foreach($tweets as $tweet) {
        // if not rt already:
           if(!$tweetModel->isTweetAlreadyRT($tweet['id_str'])){
               $bean      = $tweetModel->getBeanForInsert();
               $tweetBean = $this->hydrateTweetBean($bean, $tweet);

               // get array with occurence for 'RT', 'follow' & '@'
               $parsedTweet = $this->parseTweet($tweetBean);

               // if tweet mentions 'RT' and 'follow'
               if($parsedTweet['rt'] >= 1 && $parsedTweet['follow'] >= 1) {
                   echo "give away ! \n";
                   // and does not ask to follow more than 1 account
                   if($parsedTweet['@'] === 0 || $parsedTweet['@'] > 1 ) { // false positive possible
                       // find or create account
                       if($accountModel->isAlreadyFollowed($tweet['user']['id_str'])) {
                           $accountBean = $accountModel->load($tweet['user']['id_str']);
                       } else {
                           echo "creating new account \n";
                           $bean        = $accountModel->getBeanForInsert();
                           $accountBean = $this->hydrateAccountBean($bean, $tweet['user']);
                           // var_dump($accountBean->idTwitter);

                           // follow | todo: check http status
                          $this->results = $this->api->subscribe($accountBean->idTwitter);
                          echo "account followed \n";
                          // rt
                          $this->results = $this->api->retweet($tweetBean->idTweet);
                          echo "tweet RT \n";

                          // save
                          $accountBean->ownTweetList[] = $tweetBean;
                          $tweetBean->rt = 1;

                          $accountModel->save($accountBean);
                          $tweetModel->save($tweetBean);
                          echo "log saved \n";
                       }
                   } else {
                       // TODO Deal with more than 1 follow required
                       // Basicaly need to add API method to find user id by account namespace
                       echo "don't manage multiple follow give away yet \n";
                       echo $tweetBean->text . "\n";
                       var_dump($parsedTweet);
                   }
               } else {
                   echo "tweet is not a give away \n";
                   echo $tweetBean->text . "\n";
                   var_dump($parsedTweet);
               }
           } else {
               echo "tweet already RT: ".  $tweet['id_str'] ."\n";
           }
           // chill for like 10 - 30 sec & repeat
           $time = rand (10 , 20 );
           echo "chilling for $time \n";
           sleep(2);
        }

        echo 'Tweet : ' . $tweetModel->count() . ' <br /> ';
        echo 'Account : ' . $accountModel->count() . '<br />' ;

    }

    /**
     * TODO Refactor hydrate methods and move
     */

    private function hydrateTweetBean(OODBBean $tweetBean, array $tweet) : OODBBean
    {
        $tweetBean->idTweet = $tweet['id_str'];
        $tweetBean->text    = $tweet['full_text'];
        $tweetBean->rt      = false; // additional query required by Twitter for RT status
        $tweetBean->dateAdd = date("Y-m-d H:i:s");

        /* Set meta for long ID */
        $tweetBean->setMeta('cast.idTweet','text');

        return $tweetBean;
    }

    private function hydrateAccountBean(OODBBean $accountBean, array $account) : OODBBean
    {
        $accountBean->idTwitter      = $account['id_str'];
        $accountBean->name           = $account['name'];
        $accountBean->following      = $account['following'];
        $accountBean->dateAdd        = date("Y-m-d H:i:s");

        /* Set meta for long ID */
        $accountBean->setMeta('cast.idTwitter','text');

        // $accountBean->ownTweetList[] = $t;

        return $accountBean;
    }

    /**
     * Parse tweet and return occurence for needles
     */
    private function parseTweet(OODBBean $tweetBean)
    {
        $needles   = ['rt ', ' rt ', 'rt + follow', ' follow ', '@']; // added spaces around to avoir false positive
        $tweetText = strtolower($tweetBean->text);

        $results = [
            'rt' => 0,
            'follow' => 0,
            '@' => 0
        ];
        foreach($needles as $needle) {
            if(substr_count($tweetText , $needle) != 0) {
                switch ($needle) {
                    case 'rt + follow':
                        $results['rt'] = $results['rt'] + 1 ;
                        $results['follow'] = $results['follow'] + 1;
                        echo 'rt + follow found' . "\n";
                    break;
                    case 'rt ':
                    case ' rt ':
                        $results['rt'] = $results['rt'] + 1;
                        echo 'rt found' . "\n";
                        break;
                    case ' follow ':
                        $results['follow'] = $results['follow'] + 1;
                        echo 'follow found' . "\n";
                        break;
                    case '@':
                        $results['@'] = $results['@'] + 1;
                        echo '@ found' . "\n";
                        break;
                    default:
                    // echo 'other found' . "\n";
                    // $results[trim($needle)] =  $results[$needle] === 0 ? substr_count($tweetText , $needle) : substr_count($tweetText , $needle) + $results[$needle];
                    break;
                }
            }
        }

        // foreach($results as $needle => $count)
        // {
        //     echo "$needle: $count \n";
        // }

        return $results;
    }
}
