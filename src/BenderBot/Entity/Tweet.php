<?php

namespace BenderBot\Entity;

use BenderBot\Entity\AbstractEntity;

class Tweet extends AbstractEntity
{
    public $idTweet;

    public $text;

    public $rt;

    public $dateAdd;

    // public function __construct(array $values)
    // {
    //
    // }

    // public  function save() : int
    // {
    //     return parent::save(self);
    // }

}
