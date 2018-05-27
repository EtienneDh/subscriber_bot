<?php

namespace BenderBot\Model;

interface ModelInterface
{
    public static function getType() : string;

    public function isValid() : bool;
}
