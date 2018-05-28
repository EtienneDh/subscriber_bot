<?php

namespace BenderBot\Model;

use BenderBot\Model\BaseModel;
use BenderBot\Model\ModelInterface;
use \RedBeanPHP\R as R;

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

    public function isTweetAlreadyRT(int $tweetId) : bool
    {
        return null ===  \RedBeanPHP\R::findOne( static::TYPE, ' id_tweet = ? ', [ $tweetId ] ) ? false : true;
    }
}
