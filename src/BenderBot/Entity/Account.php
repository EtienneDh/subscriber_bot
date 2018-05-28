<?php

namespace BenderBot\Entity;

use BenderBot\Entity\AbstractEntity;

class Account extends AbstractEntity
{
    public $idTwitter;

    public $name;

    public $following;

    public $ownTweetList;

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
