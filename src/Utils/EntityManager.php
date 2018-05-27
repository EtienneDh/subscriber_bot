<?php
namespace Utils;

use BenderBot\Entity\AbstractEntity;

class EntityManager
{
    const MODEL_PATH = '';

    public function getModel(AbstractEntity $entity)
    {
        $entityName = $entity::getEntityName();
    }




}
