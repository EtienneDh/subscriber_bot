<?php
namespace Utils;

use BenderBot\Entity\AbstractEntity;

class EntityManager
{
    const NAMESPACE_DELIMITER = '\\';

    public function getModel(AbstractEntity $entity)
    {
        $entityName = $entity::getEntityName();
    }




}
