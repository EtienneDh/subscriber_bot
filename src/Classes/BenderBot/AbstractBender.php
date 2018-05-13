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

    public function setApi(APIInterface $api)
    {
        $this->api = $api;

        // Load & set Api
        $this->api = $this->getApi($this->configType); //todo : mettre ds Factory
        $this->api->setClient($this->initClient());
    }    
}
