<?php

namespace BenderBot\Model;

use \RedBeanPHP\R as R;
use BenderBot\Entity\AbstractEntity;
use BenderBot\Model\ModelInterface;


class BaseModel
{
    protected $R;

    public function __construct()
    {
        $this->R = \R;
    }    

    public function save(AbstractEntity $entity)
    {
        if($entity->isValid()) {
            $entityName = $entity::getEntityName();
            $this->R::dispense($entityName);

            return $this->R::store($entity);
        }
    }

    public function delete(AbstractEntity $entity)
    {

    }


}
