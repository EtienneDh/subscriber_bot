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

    public function searchFor(array $query, array $options = [])
    {

    }

    public function subscribe(string $twitterId, array $options = [])
    {

    }
}
