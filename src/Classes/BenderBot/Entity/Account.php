<?php

namespace BenderBot\Entity;

use BenderBot\Entity\AbstractEntity;

class Account extends AbstractEntity
{
    protected $id;

    protected $idTwitter;

    protected $name;

    protected $dateAdd;

    public function __construct(array $values)
    {
        parent::__construct($values);

        // $date = new \DateTime('now');
        // $d = $date->format('Y-m-d H:m:s');
        // $a = new Account([
        //     'id' => 1,
        //     'id_twitter' => '1234',
        //     'name' => 'Test',
        //     'date_add' => $d
        // ]);

    }

    public function setId(int $id) : Account
    {
        $this->id = $id;
        return $this;
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function setIdTwitter(string $idTwitter) : Account
    {
        $this->idTwitter = $idTwitter;
        return $this;
    }

    public function getIdTwitter() : string
    {
        return $this->idTwitter;
    }

    public function setName(string $name) : Account
    {
        $this->name = $name;
        return $this;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function setDateAdd(\DateTime $date) : Account
    {
        $this->date = $date;
        return $this;
    }

    public function getDateAdd() : \DateTime
    {
        return $this->dateAdd;
    }

}
