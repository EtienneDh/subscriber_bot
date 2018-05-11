<?php

namespace BenderBot\API;

use GuzzleHttp\Client;

interface APIInterface
{
    public function setClient(Client $client) : APIInterface;

    public function setUris(array $client) : APIInterface;

    public function searchFor(array $query, array $options);

    public function subscribe(string $twiterId, array $options);
}
