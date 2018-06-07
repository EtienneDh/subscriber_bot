<?php

namespace BenderBot;

use BenderBot\AbstractBender;
use RedBeanPHP\OODBBean;

class Bender extends AbstractBender
{
    private $results;

    private function getData() : array
    {
       // Look for tweet
        $this->results = $this->api->search();
        $retour        = json_decode($this->results->getBody(), true);
        return $retour['statuses'];
    }

    public function run()
    {
      $tweetModel   = $this->mp->getModel('tweet');
      $accountModel = $this->mp->getModel('account');        

      $tweets = $this->getData();

      foreach($tweets as $k => $tweet) {

        /* Si Concours RT alors on prend le concours */
        if(isset($tweet['retweeted_status']))
        {
          $tweet = $tweet['retweeted_status'];
        }

        echo '|'. $k . '|' . date("Y-m-d H:i:s") . '|' .$tweet['user']['name'] . "\n";

        /* Tweet déjà connu */
        if($tweetModel->isTweetAlreadyRT($tweet['id_str'])){
          echo "Tweet déjà connu \n";
          continue;
        }

        /* Chargement du tweet dans la bean */
        $bean      = $tweetModel->getBeanForInsert();
        $tweetBean = $this->hydrateTweetBean($bean, $tweet);

        // get array with occurence for 'RT', 'follow' & '@'
        $parsedTweet = $this->parseTweet($tweetBean);

        // if tweet mentions 'RT' and 'follow'
        if($parsedTweet['rt'] >= 1 && $parsedTweet['follow'] >= 1) 
        {
          echo "Concours trouvé :\n";
             // and does not ask to follow more than 1 account
          if($parsedTweet['@'] <= 1 ) 
          {
            // find or create account
            if($accountModel->isAlreadyFollowed($tweet['user']['id_str'])) {
               echo " - account existing \n";
               $accountBean = $accountModel->getAccountWithIdTwitter($tweet['user']['id_str']);
            } else {
               echo " - creating new account \n";
               $bean        = $accountModel->getBeanForInsert();
               $accountBean = $this->hydrateAccountBean($bean, $tweet['user']);

                /* Subscribe account */
                $subsribeSuccess = $this->api->subscribe($accountBean->idTwitter);
                if(null !== $subsribeSuccess) {
                    echo " - account followed \n";
                    $accountBean->following = 1;
                }
            }

            // rt
            $rtSuccess = $this->api->retweet($tweetBean->idTweet);
            if(null !== $rtSuccess) 
            {
                echo " - tweet RT \n";
                // save
                $tweetBean->rt = 1;
            }

                
            $accountBean->ownTweetList[] = $tweetBean;
            $accountBean->nbOfTweetRT++;

            $tweetModel->save($tweetBean);
            $accountModel->save($accountBean);
            echo " - Tweet & account saved \n";
          } 
          elseif($parsedTweet['@'] > 1) 
          {
            echo " - Multiple follow \n";
            // Run the regex with preg_match_all.
            preg_match_all('/@\w+/is', $tweetBean->text, $matches);

            if(is_array($matches)){
                
              foreach ($matches as $match) {
                  
                foreach ($match as $name) {
                  /* Test si le nom de compte est présent en bdd */
                  $response = $this->api->user($name);

                  if($response === null)
                  {
                        echo " - Problème de requete de compte \n";
                        continue;
                  }

                  $objAccount = json_decode($response->getBody(), true);

                  if($accountModel->isAlreadyFollowed($objAccount['id_str']))
                  {
                        $accountBean = $accountModel->getAccountWithIdTwitter($objAccount['id_str']);
                        echo " - Compte déjà en bdd : $accountBean->name \n";
                  }
                  else
                  {
                        
                        $bean        = $accountModel->getBeanForInsert();
                        $accountBean = $this->hydrateAccountBean($bean, $objAccount);
                        echo " - Création compte : $accountBean->name \n";
                  }

                  /* Subscribe account */
                  $subsribeSuccess = $this->api->subscribe($accountBean->idTwitter);
                  if(null !== $subsribeSuccess) 
                  {
                        echo " - Compte suivi \n";
                        $accountBean->following = 1;
                  }

                  // rt
                  $rtSuccess = $this->api->retweet($tweetBean->idTweet);
                  if(null !== $rtSuccess) 
                  {
                        echo " - Tweet RT \n";
                        // save
                        $tweetBean->rt = 1;
                  }
                      
                  $accountBean->ownTweetList[] = $tweetBean;
                  $accountBean->nbOfTweetRT++;

                  $tweetModel->save($tweetBean);
                  $accountModel->save($accountBean);

                  $time = rand(3,5);
                  echo " - Attente de $time secondes \n";
                  sleep($time);
                }
              }
            }
          }
        }
        else
        {
          echo "Pas de concours trouvé \n";
        }
        
        // chill for like 10 - 30 sec & repeat
        $time = rand (5 , 10);

        echo "attente de $time secondes entre deux tweets\n";
        sleep($time);
      }
      echo 'Nombre de tweet : ' . $tweetModel->count() . "\n";
      echo 'Nombre de compte : ' . $accountModel->count() . "\n" ;
    }

    /**
     * TODO Refactor hydrate methods and move
     */

    private function hydrateTweetBean(OODBBean $tweetBean, array $tweet) : OODBBean
    {
        $tweetBean->idTweet    = (int) $tweet['id'];
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
      $needles = ['rt ', 'follow', '@'] ;
      $tweetText = strtolower($tweetBean->text);

      $results = [
          'rt' => 0,
          'follow' => 0,
          '@' => 0
      ];

      foreach($needles as $needle) {
          $match = substr_count(strtolower($tweetText) , $needle);
          if($match != 0) {
              switch ($needle) {
                  case 'rt ':
                      $results['rt'] = $results['rt'] + $match;
                      break;
                  case 'follow':
                      $results['follow'] = $results['follow'] + $match;
                      break;
                  case '@':
                      $results['@'] = $results['@'] + $match;
                      break;
              }
          }
      }

      echo "RT => ".$results['rt']." | FOLLOW => ".$results['follow']." | @ => ".$results['@']." \n";
      return $results;
    }
}
