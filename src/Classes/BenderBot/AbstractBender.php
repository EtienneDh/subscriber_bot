<?php

namespace BenderBot;

use BenderBot\API\APIInterface;

/**
 * Base class for Bender.
 */
abstract class AbstractBender
{
    protected $appName;
    protected $api;
    protected $query;
    protected $options;

    abstract public function run();

    public function setApi(APIInterface $api)
    {
        $this->api = $api;
    }

    public function setAppName(string $appName)
    {
        $this->appName = $appName;
    }
}
