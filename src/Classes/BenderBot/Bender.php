<?php

namespace BenderBot;

use BenderBot\AbstractBender;
use BenderBot\Entity\Account;

class Bender extends AbstractBender
{
    private $searchResult = array();

    public function searchFor(array $query, array $options = [])
    {

        echo "Searching for " . implode(', ', $query) . " key words ...\n";

        $results = $this->client->get('search/tweets.json', [
            'query' => [
                'q' => implode('%20', $query),
                'result_type' => 'mixed'
            ]
        ]);

        $tweets =  json_decode($results->getBody(), true);

        // foreach($tweets['statuses'] as $tweet) {
        //     $tweetInfo = [
        //             'idTwitter' => $tweet['id_str'],
        //             'userName'  => $tweet['user']['name'],
        //             'userId'  => $tweet['user']['id_str'],
        //     ];
        //     $this->searchResult[] = $tweetInfo;
        // }

        // echo count($this->searchResult) . " tweet founds \n";
        // $tweet = $this->searchResult[1];
        //
        // echo "about to follow " . $tweet['userName'] . " ...\n";
        // $this->subscribe($tweet['userId']);



    }

    public function subscribe($idTwitter)
    {
        $results = $this->client->post('friendships/create.json', [
            'query' => [
                'user_id' => $idTwitter,
                'follow' => true
            ]
        ]);
        echo "Done ! \n";
        // var_dump(json_decode($results->getBody(), true));
        exit;
    }
}
