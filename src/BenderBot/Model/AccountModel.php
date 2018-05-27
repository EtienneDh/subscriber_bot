<?php

namespace BenderBot\Model;

use BenderBot\Model\BaseModel;
use BenderBot\Model\ModelInterface;

class AccountModel extends BaseModel implements ModelInterface
{
    // Validation logic 
    public function isValid() : bool
    {
        echo "I got called ! \n";
        return true;
    }

    public function isAlreadyFollowed() : bool
    {
        return false;
    }
}
