<?php

namespace BenderBot\Model;

use BenderBot\Model\BaseModel;
use BenderBot\Model\ModelInterface;

class TweetModel extends BaseModel implements ModelInterface
{
    /**
     * Mandatory, used by BaseModel to resolve bean type
     */
    const TYPE = "tweet";

    public static function getType() : string
    {
        return self::TYPE;
    }

    // Validation logic
    public function isValid() : bool
    {
        return true;
    }

    public function isAlreadyFollowed() : bool
    {
        return false;
    }
}
