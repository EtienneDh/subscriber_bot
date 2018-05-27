<?php

namespace Utils;

use Utils\Tools;

use BenderBot\AbstractBender;
use BenderBot\Bender;
use BenderBot\BenderConfigurator;
use Utils\EntityManager;


/**
 * Configure and provide class instances.
 */
class Factory
{
    public static function getBender(string $appName) : AbstractBender
    {
        $benderBot = new Bender();
        BenderConfigurator::init($appName);

        $benderBot->setAppName($appName)
            ->setEntityManager(new EntityManager())
            ->setApi(BenderConfigurator::getApi())
        ;        

        return $benderBot;
    }
}
