<?php

namespace BenderBot\Model;

use BenderBot\Model\BaseModel;
use BenderBot\Model\ModelInterface;

class AccountModel extends BaseModel implements ModelInterface
{
    const TYPE = "account";

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
