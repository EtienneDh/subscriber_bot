<?php

namespace Utils;

use Utils\Tools;

use BenderBot\AbstractBender;
use BenderBot\Bender;
use BenderBot\BenderConfigurator;


/**
 * Configure and provide class instances.
 */
class Factory
{
    public static function getBender(string $appName) : AbstractBender
    {
        $benderBot = new Bender();
        $benderBot->setAppName($appName);

        BenderConfigurator::init($appName);
        $benderBot->setApi(BenderConfigurator::getApi());

        return $benderBot;
    }
}
