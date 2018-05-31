<?php

namespace BenderBot\API;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\ClientException;

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

    public function setSearch(array $search) : APIInterface
    {
        $this->search = $search;
        return $this;
    }

    public function search() : Response
    {
        $route = $this->uris['searchUri'];

        return $this->client->get($route, [
            'query' => [
                'q' => implode('%20', $this->search['term']),
                'result_type' => 'mixed',
                'tweet_mode' => 'extended'/*,
                'count' => 100*/
            ]
        ]);
    }

    public function subscribe(string $idTwitter, array $options = []) : ?Response
    {
        $route    = $this->uris['followUri'];
        $response = null;

        try {
            $response = $this->client->post($route, [
                'query' => [
                    'user_id' => $idTwitter,
                    'follow'  => true
                ]
            ]);
        } catch (ClientException $e) {
            echo "Error while subscribing to $idTwitter \n";
            // echo $e . "\n";
        }

        return $response;
    }

    public function retweet(string $tweetId) : ?Response
    {
        $route = $this->uris['retweetUri']
            . $tweetId
            . '.json';
            
        $response = null;

        try {
            $this->client->post($route, []);
        } catch(ClientException $e) {
            echo "Error while retweeting  $tweetId \n";
            echo $e ."\n";
            exit;
        }

        return $response;
    }

    // private function decode(string $result, bool $asArray = true) : array
    // {
    //     return json_decode($results, $asArray);
    // }
}
