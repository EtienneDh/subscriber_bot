<?php

namespace BenderBot;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;

use Utils\Tools;
/**
 * Base class for Bender.
 *
 * Load config from .json and create and oAuth authenticated client
 **/
abstract class AbstractBender
{
    const CONFIG_DIRECTORY = __DIR__  . '/../../../config/';

    // Oauth Info
    protected $consumerKey;
    protected $consumerSecret;
    protected $token;
    protected $tokenSecret;

    // url
    protected $baseUri;
    protected $followUri;
    protected $repostUri;

    // GuzzleHttp Client
    protected $client;

    public function loadConfig(string $config) : bool
    {
        try {
            $config = Tools::extractJsonFromFile(self::CONFIG_DIRECTORY . $config);
            $this->setParameters($config);
        } catch (\Exception $e) {
            echo $ex;

            return false;
        }

        return true;
    }

    public function initClient()
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

            $this->client = new Client([
                'base_uri' => $this->baseUri,
                'handler' => $stack,
                'auth' => 'oauth',
            ]);

        } catch (\Exception $e) {
            echo $e . "\n";
            exit('Cannot initialize client');
        }

    }

    private function setParameters(array $configParameters)
    {
        foreach($configParameters as $params) {
            foreach($params as $property => $value) {
                if(property_exists($this, $property)) {
                    $this->{$property} = $value;
                } else {
                    exit("Unknown parameter: $property \n");
                }
            }
        }
    }
}
