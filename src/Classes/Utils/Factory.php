<?php

namespace Utils;

use Tools;

/**
 * Provide Db Connection, will provide bots when more config is added.
 */
class Factory
{
    const CONFIG_DIRECTORY = __DIR__  . '/../../../config/';
    const PARAMETERS_FILE  = 'parameters';

    private static $dbInstance;

    public static function getDb()
    {
        if(!isset(self::$dbInstance)) {

            $dbParams = Tools::extractJsonFromFile(self::CONFIG_DIRECTORY . self::PARAMETERS_FILE);
            $host     = $dbParams['host'];
            $dbname   = $dbParams['name'];
            $user     = $dbParams['user'];
            $password = $dbParams['password'];

            try {
                self::dbInstance = new PDO("mysql:host=$host;dbname=$name;charset=utf8", $user, $password);
            } catch (\Exception $e) {
                exit("Failed to connect to $dbName or MySQL server");
            }
        }

        return self::$dbInstance;
    }


}
