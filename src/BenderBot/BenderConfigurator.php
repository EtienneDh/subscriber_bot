<?php

namespace BenderBot;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Utils\Tools;
use BenderBot\API\APIInterface;

class BenderConfigurator
{
    const API_NAMESPACE = "BenderBot\API";

    public static $appName;

    public static $client;

    public static $uris;

    public static $search;

    /**
     * Fetch parameters from config files, create Client
     */
    public static function init(string $appName)
    {

        self::$appName = $appName;

        // Get application parameters from app_name.json
        try {
            $params = Tools::extractJsonFromFile(self::$appName);
        } catch (\Exception $e) {
            exit("Cannot load application parameters \n");
        }

        // set Client
        if(isset($params['oauth']) && isset($params['uris']['baseUri'])) {
            self::startClient($params['oauth'], $params['uris']['baseUri']);
        } else {
            exit("Missing parameters: oauth or Uris \n");
        }

        // set uris
        self::$uris = $params['uris'];

        // Set Term of Search
        if(isset($params['search']) && isset($params['search']['term'])) {
            self::$search = $params['search'];
        } else {
            exit("Missing parameters: Search term \n");
        }
    }

    /**
     * Instante API from app name
     */
    public static function getApi() : APIInterface
    {
        $className = ucfirst(self::$appName) . 'API';
        $fullName  = self::API_NAMESPACE . "\\" . $className;
        // exit(var_dump($fullName));
        try {
            $api = new $fullName;
        } catch (\Exception $e) {
            exit("Failed to instantiate Api: $className \n");
        }

        $api->setClient(self::$client);
        $api->setUris(self::$uris);
        $api->setSearch(self::$search);

        return $api;
    }

    /**
     * Instantiate and set up guzzle client.
     */
    private static function startClient(array $oAuth, string $baseUri)
    {
        try {
            $stack = HandlerStack::create();

            $middleware = new Oauth1([
                'consumer_key'    => $oAuth['consumerKey'],
                'consumer_secret' => $oAuth['consumerSecret'],
                'token'           => $oAuth['token'],
                'token_secret'    => $oAuth['tokenSecret'],
            ]);
            $stack->push($middleware);

            $client = new Client([
                'base_uri' => $baseUri,
                'handler' => $stack,
                'auth' => 'oauth',
            ]);
        } catch (\Exception $e) {
            echo $e . "\n";
            exit('Cannot initialize client');
        }

        self::$client = $client;
    }
}
