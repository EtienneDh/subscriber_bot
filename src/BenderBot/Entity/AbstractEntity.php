<?php

namespace BenderBot\Entity;

use \RedBeanPHP\R as R;

/**
 * Base class of entities.
 */
abstract class AbstractEntity
{
    const NAMESPACE_DELIMITER = '\\';

    public static function getEntityName() : string
    {
        $classNamespace = static::class;
        $a = explode(self::NAMESPACE_DELIMITER, $classNamespace);

        return strtolower(array_pop($a));
    }
}
