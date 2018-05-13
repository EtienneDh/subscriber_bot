<?php

namespace BenderBot;

use BenderBot\AbstractBender;
use BenderBot\Entity\Account;

class Bender extends AbstractBender
{
    public function run()
    {
        exit(var_dump($this->api));
    }
}
