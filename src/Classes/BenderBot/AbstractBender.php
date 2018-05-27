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

    public function setApi(APIInterface $api)
    {
        $this->api = $api;
    }

    public function setAppName(string $appName)
    {
        $this->appName = $appName;
    }

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }
}
