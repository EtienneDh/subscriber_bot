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

        foreach($tweets as $k => $tweet) {
          

           echo '-'. $k . ' | ' .$tweet['id'] . ' : ' .$tweet['user']['name'] . "\n";

           if(isset($tweet['retweeted_status'])){
              echo "Tweet RT dans la recherche\n";
              continue;
           }

        // if not rt already:
           if(!$tweetModel->isTweetAlreadyRT($tweet['id_str']) && !$tweet['retweeted']){
               $bean      = $tweetModel->getBeanForInsert();
               $tweetBean = $this->hydrateTweetBean($bean, $tweet);

               // get array with occurence for 'RT', 'follow' & '@'
               $parsedTweet = $this->parseTweet($tweetBean);


               // if tweet mentions 'RT' and 'follow'
               if($parsedTweet['rt'] >= 1 && $parsedTweet['follow'] >= 1) {
                   echo "give away ! \n";
                   // and does not ask to follow more than 1 account
                   if($parsedTweet['@'] <= 1 ) { // false positive possible
                       // find or create account
                       if($accountModel->isAlreadyFollowed($tweet['user']['id_str'])) {
                           echo "account existing \n";
                           $accountBean = $accountModel->getAccountWithIdTwitter($tweet['user']['id_str']);
                       } else {
                           echo "creating new account \n";
                           $bean        = $accountModel->getBeanForInsert();
                           $accountBean = $this->hydrateAccountBean($bean, $tweet['user']);

                            /* Subscribe account */
                            $subsribeSuccess = $this->api->subscribe($accountBean->idTwitter);
                            if(null !== $subsribeSuccess) {
                                echo "account followed \n";
                                $accountBean->following = 1;
                            }
                      }

                      // rt
                      $rtSuccess = $this->api->retweet($tweetBean->idTweet);
                      if(null !== $rtSuccess) {
                          echo "tweet RT \n";

                          // save
                          $tweetBean->rt = 1;
                      }

                      
                      $accountBean->ownTweetList[] = $tweetBean;
                      $accountBean->nbOfTweetRT++;

                      $tweetModel->save($tweetBean);
                      $accountModel->save($accountBean);
                      echo "Tweet & account saved \n";
                   } else {
                       
                       echo "Multiple follow \n";
                        // Run the regex with preg_match_all.
                        preg_match_all('/@\w+/is', $tweetBean->text, $matches);

                        if(is_array($matches)){
                          
                          foreach ($matches as $match) {
                          
                            foreach ($match as $name) {
                            /* Test si le nom de compte est présent en bdd */
                            $accountName = substr($name, 1);
                             
                            if(!$accountModel->isAlreadySave($accountName)){ 
                                                            
                              $objAccount = json_decode($this->api->user($accountName)->getBody(), true);

                              $bean        = $accountModel->getBeanForInsert();
                              $accountBean = $this->hydrateAccountBean($bean, $objAccount);

                              /* Subscribe account */
                              $subsribeSuccess = $this->api->subscribe($objAccount->idTwitter);
                              if(null !== $subsribeSuccess) {
                                  echo "account followed \n";
                                  $accountBean->following = 1;
                              }

                              $accountModel->save($accountBean);
                            }
                          }
                        }
                      }
                    }
               } else {
                   echo "tweet is not a give away \n";
               }
           } else {
               echo "tweet already in data base \n";
           }

          echo 'Tweet : ' . $tweetModel->count() . "\n";
          echo 'Account : ' . $accountModel->count() . "\n" ;
           // chill for like 10 - 30 sec & repeat
          $time = rand (20 , 40);

          echo "attente de $time secondes entre deux tweets\n";
          sleep($time);
        }

        $reload = rand(300, 600);
        echo "attente de ". intval($reload/60) ." minutes entre deux recherches\n";
        sleep($reload);
        $this->run();

      }

    /**
     * TODO Refactor hydrate methods and move
     */

    private function hydrateTweetBean(OODBBean $tweetBean, array $tweet) : OODBBean
    {
        $tweetBean->idTweet    = (int)    $tweet['id'];
        $tweetBean->idTweetStr = (string) $tweet['id_str'];
        $tweetBean->text       = $tweet['full_text'];
        $tweetBean->rt         = false;
        $tweetBean->dateAdd    = date("Y-m-d H:i:s");

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

        return $accountBean;
    }

    /**
     * Parse tweet and return occurence for needles
     */
    private function parseTweet(OODBBean $tweetBean)
    {
        $needles = ['rt + follow' , 'RT + Follow' , 'Follow + RT' , 'follow + rt', 'follow+rt', 'rt+follow' , 'rt ' , ' rt' , '#rt' , 'follow' , 'Follow ','@' ] ;
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
                    case 'RT + Follow':
                    case 'Follow + RT':
                    case 'follow + rt':
                    case 'follow+rt':
                    case 'rt+follow' :
                        $results['rt']     = $results['rt'] + 1 ;
                        $results['follow'] = $results['follow'] + 1;
                    break;
                    case 'rt ':
                    case ' rt ':
                    case '#rt':
                        $results['rt'] = $results['rt'] + 1;
                        break;
                    case ' follow ':
                    case ' Follow ':
                        $results['follow'] = $results['follow'] + 1;
                        break;
                    case '@':
                        $results['@'] = $results['@'] + 1;
                        break;
                    default:
                    break;
                }
            }
        }
        return $results;
    }
}
