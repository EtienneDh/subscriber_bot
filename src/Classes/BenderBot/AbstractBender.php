<?php

namespace BenderBot;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;

abstract class AbstractBender
{
    const CONFIG_DIRECTORY = __DIR__  . '/../../../config/';
    const CONFIG_FORMAT    = '.json';

    // Oauth Info
    protected $consumerKey;
    protected $consumerSecret;
    protected $token;
    protected $tokenSecret;

    protected $baseUri;

    protected $client;

    public function loadConfig(string $config) : bool
    {
        try {
            $config = file_get_contents(self::CONFIG_DIRECTORY . $config . self::CONFIG_FORMAT);
            $this->setParameters(json_decode($config));
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

    private function setParameters(\stdClass $config)
    {
        try {
            $this->setOauth($config->oauth);
            $uris          = $config->uris;
            $this->baseUri = $uris->baseUri;

        } catch(\Exception $ex) {
            exit("Could not initialize parameters");
        }
    }

    private function setOauth(\stdClass $oauthParams)
    {
        $this->consumerKey    = $oauthParams->consumerKey;
        $this->consumerSecret = $oauthParams->consumerSecret;
        $this->token          = $oauthParams->token;
        $this->tokenSecret    = $oauthParams->tokenSecret;
    }
}
