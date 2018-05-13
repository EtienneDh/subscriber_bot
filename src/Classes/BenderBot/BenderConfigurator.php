<?php

namespace BenderBot;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Utils\Tools;
use BenderBot\API\APIInterface;

abstract class BenderConfigurator
{
    const API_DIRECTORY    = __DIR__ . '/API/';

    public static $appName;

    public static $client;

    public static $uris;

    public static function setAppName(string $appName)
    {
        self::$appName = $appName;
    }

    /**
     * Fetch parameters from config files, create Client
     */
    public static function init()
    {
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
    }

    public static function getApi() : APIInterface
    {
        try {
            $className = ucfirst(self::$appName) . 'API';
            $file      = self::API_DIRECTORY . $className;
            require $file;
            $api = new $className();
        } catch (\Exception $e) {
            echo "API $className could not be found \n";
            exit;
        }

        $api->setClient(self::$client);
        $api->setUris(self::$uris);

        return $api;
    }

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
