<?php

namespace BenderBot\Model;

use \RedBeanPHP\R as R;
use BenderBot\Entity\AbstractEntity;
use BenderBot\Model\ModelInterface;

class BaseModel
{

    public function getBeanForInsert(string $entityName)
    {
        return R::dispense($entityName);
    }

    public function save(\RedBeanPHP\OODBBean $entity) : int
    {
        if($this->isValid()) {
            try {
                $id = R::store($entity);
            } catch(\Exception $e) {
                "Fail to save " . $entityName . " to database \n";
            }

            return $id;
        }
    }

    public function delete(AbstractEntity $entity)
    {

    }


}
