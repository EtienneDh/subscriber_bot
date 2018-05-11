<?php

namespace BenderBot;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;

use Utils\Tools;
use BenderBot\API\APIInterface;

/**
 * Base class for Bender.
 *
 * Load config from .json and create and oAuth authenticated client
 **/
abstract class AbstractBender
{
    const CONFIG_DIRECTORY = __DIR__  . '/../../../config/';
    const CONFIG_FORMAT    = '.json';
    const API_DIRECTORY    = __DIR__ . '/API/';

    private $configType;

    // Oauth Info
    protected $consumerKey;
    protected $consumerSecret;
    protected $token;
    protected $tokenSecret;

    protected $api;

    protected $baseUri;

    public function loadConfig(string $configType) : bool
    {
        $this->configType = $configType;

        try {
            // Load & set Parameters
            $params = Tools::extractJsonFromFile(self::CONFIG_DIRECTORY . $this->configType);
            $this->setParameters(json_decode($params, true));
        } catch (\Exception $e) {
            echo $ex;
            return false;
        }

        return true;
    }

    public function setApi()
    {
        // Load & set Api
        $this->api = $this->getApi($this->configType); //todo : mettre ds Factory
        $this->api->setClient($this->initClient());
    }

    public function initClient() : Client
    {
        try {
            $stack = HandlerStack::create();

            $middleware = new Oauth1([
                'consumer_key'    => $this->consumerKey,
                'consumer_secret' => $this->consumerSecret,
                'token'           => $this->token,
                'token_secret'    => $this->tokenSecret,
            ]);
            $stack->push($middleware);

            $client = new Client([
                'base_uri' => $this->baseUri,
                'handler' => $stack,
                'auth' => 'oauth',
            ]);
        } catch (\Exception $e) {
            echo $e . "\n";
            exit('Cannot initialize client');
        }

        return $client;
    }

    private function setParameters(array $configParameters)
    {
        foreach($configParameters as $params) {
            foreach($params as $property => $value) {
                if($params !== 'uris') {
                    if(property_exists($this, $property)) {
                        $this->{$property} = $value;
                    } else {
                        exit("Unknown parameter: $property \n");
                    }
                } else {
                    $this->uris[$property] = $value;
                }
            }
        }
    }

    private function getApi(string $config) : APIInterface
    {
        try {
            $className = ucfirst($config) . 'API';
            $file      = self::API_DIRECTORY . $className;
            require $file;
            $api = new $className();
        } catch (\Exception $e) {
            echo "API $className could not be load \n";
            exit;
        }

        return $api;
    }
}
