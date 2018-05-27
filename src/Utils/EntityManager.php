<?php
namespace Utils;

use BenderBot\Entity\AbstractEntity;
use BenderBot\Model\ModelInterface;

class EntityManager
{
    const MODEL_NAMESPACE = 'BenderBot\Model';

    public function getModel(string $modelName) : ModelInterface
    {
        $className = ucfirst($modelName) . 'Model';
        $fullName  = self::MODEL_NAMESPACE . "\\" . $className;

        try {
            $model = new $fullName;
        } catch (\Exception $e) {
            exit("Failed to instantiate Model: $className in EntityManager\n");
        }

        return $model;
    }
}
