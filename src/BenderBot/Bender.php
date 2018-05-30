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
                   // deal with @follow
                   $accountToFollow = $this->getAccountsToFollow($tweetBean, $parsedTweet);
                   continue;
                   // find or create account
                   if($accountModel->isAlreadyFollowed($tweet['user']['id_str'])) {
                       echo "account existing \n";
                       $accountBean = $accountModel->load($tweet['user']['id_str']);
                   } else {
                       echo "creating new account \n";
                       $bean        = $accountModel->getBeanForInsert();
                       $accountBean = $this->hydrateAccountBean($bean, $tweet['user']);

                       // follow | todo: check http status
                       $subsribeSuccess = $this->api->subscribe($accountBean->idTwitter);
                       if(null !== $subsribeSuccess) {
                           echo "account followed \n";
                       }
                  }

                      // rt
                      $rtSuccess = $this->api->retweet($tweetBean->idTweet);
                      if(null !== $rtSuccess) {
                          echo "tweet RT \n";
                      }

                      // save
                      $accountBean->ownTweetList[] = $tweetBean;
                      $accountBean->nbOfTweetRT++;
                      $tweetBean->rt = 1;

                      $accountModel->save($accountBean);
                      $tweetModel->save($tweetBean);
                      echo "log saved \n";
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
        $tweetBean->idTweet    = (int)    $tweet['id'];
        $tweetBean->idTweetStr = (string) $tweet['id_str'];
        $tweetBean->text       = $tweet['full_text'];
        $tweetBean->rt         = false; // additional query required by Twitter for RT status
        $tweetBean->dateAdd    = date("Y-m-d H:i:s");

        /* Set meta for long ID */
        $tweetBean->setMeta('cast.idTweetStr','text');

        return $tweetBean;
    }

    private function hydrateAccountBean(OODBBean $accountBean, array $account) : OODBBean
    {
        $accountBean->idTwitter      = (int)    $account['id'];
        $accountBean->idTwitterStr   = (string) $account['id_str'];
        $accountBean->name           = $account['name'];
        $accountBean->following      = $account['following'];
        $accountBean->nbOfTweetRT    = 0;
        $accountBean->dateAdd        = date("Y-m-d H:i:s");

        /* Set meta for long ID */
        $accountBean->setMeta('cast.idTwitterStr','text');

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
                        $results['rt']     = $results['rt'] + 1 ;
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

    private function getAccountsToFollow(OODBBean $tweetBean, array $parsedTweet) : array
    {
        $accountsToFollow = [];
        if($parsedTweet['@'] > 1) {
            // replace '@' in text body with 'at.' (can't find a way to match @ with following regex -.-)
            $text = str_replace("@", "at.", $tweetBean->text);
            // Set the regex.
            $regex = '/(?<=\bat.)(?:[\w-]+)/is';
            // Run the regex with preg_match_all.
            preg_match_all($regex, $text, $matches);
            // add to array
            foreach($matches as $match) {
                $accountsToFollow['name'] = $match;
            }
            var_dump($text);
            $accountsToFollow['id'] = $tweetBean['user']['id_str'];
            exit(var_dump($accountsToFollow));
        }

        // add tweet //
        return $accountsToFollow;
    }

}
