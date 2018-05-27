<?php

namespace BenderBot;

use BenderBot\API\APIInterface;
use Utils\ModelProvider;

/**
 * Base class for Bender.
 */
abstract class AbstractBender
{
    protected $appName;
    protected $api;
    protected $mp;


    abstract public function run();

    public function setApi(APIInterface $api) : AbstractBender
    {
        $this->api = $api;

        return $this;
    }

    public function setAppName(string $appName) : AbstractBender
    {
        $this->appName = $appName;

        return $this;
    }

    public function setModelProvider(ModelProvider $mp) : AbstractBender
    {
        $this->mp = $mp;

        return $this;
    }
}
