<?php

namespace BenderBot\Model;

use \RedBeanPHP\R as R;
use RedBeanPHP\OODBBean;

use BenderBot\Model\ModelInterface;

abstract class BaseModel
{
    /**
     * Return Bean empty Object for create operation.
     * Overload method in Model children for advanced config.
     */
    public function getBeanForInsert()
    {
        return R::dispense(static::TYPE);
    }

    public function save(OODBBean $entity) : int
    {
        // implement method in children to validate here
        if($this->isValid()) {
            try {
                $id = R::store($entity);
            } catch(\Exception $e) {
                "Fail to save " . $entityName . " to database \n";
            }

            return $id;
        }
    }

    public function load(int $id) : OODBBean
    {
        return R::load(static::TYPE, $id);
    }

    public function delete(AbstractEntity $entity)
    {

    }


}
