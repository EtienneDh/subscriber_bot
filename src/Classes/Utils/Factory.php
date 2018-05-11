<?php

namespace Utils;

/**
 * Provide Db Connection, will provide bots when more config is added.
 */
class Factory
{
    private static $dbInstance;

    public static function getDb()
    {
        if(!isset(self::$dbInstance)) {
            try {
                self::dbInstance = new PDO('mysql:host=localhost;dbname=bender;charset=utf8', 'root', '');
            } catch (\Exception $e) {
                exit("Failed to connect to $dbName or MySQL server");
            }
        }
    }


}
