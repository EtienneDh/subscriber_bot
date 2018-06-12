<?php

namespace BenderBot\API;

use GuzzleHttp\Client;

interface APIInterface
{
    public function setClient(Client $client) : APIInterface;

    public function setUris(array $uris) : APIInterface;

    public function setSearch(array $search) : APIInterface;

    public function setComments(array $comments) : APIInterface;

    public function setOptions(array $options) : APIInterface;

    public function getOptions() : array;

    public function search();

    public function subscribe(string $twiterId, array $options);
}
