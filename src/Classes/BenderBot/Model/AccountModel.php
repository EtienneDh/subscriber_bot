<?php

namespace BenderBot\Model;

use BenderBot\Model\BaseModel;
use BenderBot\Model\ModelInterface;

class AccountModel extends BaseModel implements ModelInterface
{
    public function isValid() : bool
    {
        return true;
    }
}
