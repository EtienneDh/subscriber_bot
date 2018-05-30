<?php

namespace BenderBot\Model;

use BenderBot\Model\BaseModel;
use BenderBot\Model\ModelInterface;
use \RedBeanPHP\R;

class AccountModel extends BaseModel implements ModelInterface
{
    /**
     * Mandatory, used by BaseModel to resolve bean type
     */
    const TYPE = "account";

    public static function getType() : string
    {
        return self::TYPE;
    }

    // Validation logic
    public function isValid() : bool
    {
        return true;
    }

    public function isAlreadyFollowed(string $idTwitter) : bool
    {
        return null ===  \RedBeanPHP\R::findOne( static::TYPE, ' id_tweeter = ? ', [ $idTwitter ] ) ? false : true;
    }
}
