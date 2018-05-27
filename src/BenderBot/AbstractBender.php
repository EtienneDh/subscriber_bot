<?php

namespace BenderBot;

use BenderBot\API\APIInterface;
use Utils\EntityManager;

/**
 * Base class for Bender.
 */
abstract class AbstractBender
{
    protected $appName;
    protected $api;
    protected $em;


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

    public function setEntityManager(EntityManager $em) : AbstractBender
    {
        $this->em = $em;

        return $this;
    }
}
