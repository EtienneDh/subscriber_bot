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
    const PARAMETERS_FILE  = 'parameters';

    private static $dbInstance;

    public static function getDb()
    {
        if(!isset(self::$dbInstance)) {

            $dbParams = Tools::extractJsonFromFile(self::PARAMETERS_FILE);
            $host     = $dbParams['host'];
            $dbname   = $dbParams['name'];
            $user     = $dbParams['user'];
            $password = $dbParams['password'];

            try {
                self::$dbInstance = 'yoyo';
                // self::dbInstance = new PDO("mysql:host=$host;dbname=$name;charset=utf8", $user, $password);
            } catch (\Exception $e) {
                exit("Failed to connect to $dbName or MySQL server \n");
            }
        }

        return self::$dbInstance;
    }

    public static function getBender(string $appName) : AbstractBender
    {
        $benderBot = new Bender();

        BenderConfigurator::setAppName($appName);
        BenderConfigurator::init();

        $benderBot->setApi(BenderConfigurator::getApi());

        return $benderBot;
    }

}
