<?php

namespace BenderBot\API;

use GuzzleHttp\Client;
use BenderBot\API\APIInterface;

class TwitterAPI implements APIInterface
{
    private $client;
    private $uris = array();

    public function setClient(Client $client) : APIInterface
    {
        $this->client = $client;
        return $this;
    }

    public function setUris(array $uris) : APIInterface
    {
        $this->uris = $uris;
        return $this;
    }

    public function search(array $query, array $options = [])
    {
        $results = $this->client->get('search/tweets.json', [
            'query' => [
                'q' => implode('%20', $query),
                'result_type' => 'mixed'
            ]
        ]);

        return $results;
    }

    public function subscribe(string $twitterId, array $options = [])
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
