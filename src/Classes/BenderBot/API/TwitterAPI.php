<?php

namespace BenderBot\API;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
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

    public function search(array $query, array $options = []) : Response
    {
        $route = $this->uris['baseUri'] . $this->uris['searchUri'];

        return $this->client->get($route, [
            'query' => [
                'q' => implode('%20', $query),
                'result_type' => 'mixed',
                'tweet_mode' => 'extended'
            ]
        ]);
    }

    public function subscribe(string $twitterId, array $options = []) : Response
    {
        $route = $this->uris['baseUri'] . $this->uris['followUri'];

        return $this->client->post($route, [
            'query' => [
                'user_id' => $idTwitter,
                'follow'  => true
            ]
        ]);
    }

    // private function decode(string $result, bool $asArray = true) : array
    // {
    //     return json_decode($results, $asArray);
    // }
}
