<?php

namespace BenderBot;

use BenderBot\API\APIInterface;

/**
 * Base class for Bender.
 *
 **/
abstract class AbstractBender
{
    protected $appName;
    protected $api;

    abstract public function run();

    public function setApi(APIInterface $api)
    {
        $this->api = $api;
    }

    public function setAppName(string $appName)
    {
        $this->appNAme = $appName;
    }
}
