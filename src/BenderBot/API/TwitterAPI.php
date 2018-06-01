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
        $response = null;
        $randTerm = array_rand($this->search['term']);

        echo "Terme recherché : " . $this->search['term'][$randTerm] ."\n";

        try {
            $response =  $this->client->get($route, [
                'query' => [
                    'q' => str_replace(' ', '%20', $this->search['term'][$randTerm]),
                    'result_type' => 'mixed',
                    'tweet_mode' => 'extended',
                    'count' => 100,
                    'lang' => 'fr'
                ]
            ]);
        } catch (ClientException $e) {
            echo "Error while search tweet\n";
        }

        return $response;
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
            echo "Error while subscribing to $idTwitter\n";
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
            $response = $this->client->post($route, []);
        } catch(ClientException $e) {
            echo "Error while retweeting  $tweetId\n";
            
        }

        return $response;
    }

    public function user(string $screenName) : ?Response
    {
        $route = $this->uris['userUri'];
        $response = null;

        try {
            $response = $this->client->get($route, [
                'query' => [
                    'screen_name' => $screenName
                ]
            ]); 
        } catch(ClientException $e) {
            echo "Error while get user's information\n";
            
        }  

        return $response;
    }

    // private function decode(string $result, bool $asArray = true) : array
    // {
    //     return json_decode($results, $asArray);
    // }
}
