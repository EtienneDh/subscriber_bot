<?php

namespace BenderBot\Api;

use GuzzleHttp\Client;
use BenderBot\API\APIInterface;

class TwitterApi implements APIInterface
{
    private $client;
    private $uris = array();

    public function setClient(Client $client)
    {
        $this->client = $client;
        return $this;
    }

    public function setUris(array $uris)
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

    }
}
