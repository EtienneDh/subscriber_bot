<?php

namespace BenderBot\Model;

use \RedBeanPHP\R as R;
use RedBeanPHP\OODBBean;

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

    public function count() : int
    {
        return R::count(static::TYPE);
    }

    public function save(OODBBean $entity) : int
    {
        // implement method isValid in children to validate here
        if($this->isValid()) {
            try {
                $id = R::store($entity);
            } catch(\Exception $e) {
                "Fail to save " . static::TYPE . " to database \n";
            }

            return $id;

        } else {
            exit("Could not save " . static::TYPE . ": Unvalid");
        }
    }

    public function load(int $id) : OODBBean
    {
        return R::load(static::TYPE, $id);
    }

    public function delete(OODBBean $entity)
    {

    }


}
