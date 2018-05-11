<?php

namespace BenderBot;

use BenderBot\AbstractBender;

class Bender extends AbstractBender
{
    public function lookFor(array $query)
    {

        return json_encode([
            'Bob' => '1293219',
            'Mark' => '2193841212'
        ]);


        $results = $this->client->get('search/tweets.json', [
            'query' => [
                'q' => implode('%20', $query),
                'result_type' => 'mixed'
            ]
        ]);

        $tweets =  json_decode($results->getBody(), true);
        foreach($tweets['statuses'] as $t) {
            // echo $t['text'];
            echo $t['user']['name'] . "\n";
            echo $t['id_str'] . "\n";
        }
        exit;
    }
}
