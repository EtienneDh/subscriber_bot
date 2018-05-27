<?php
namespace Utils;

use BenderBot\Model\ModelInterface;

class ModelProvider
{
    const MODEL_NAMESPACE = 'BenderBot\Model';

    public function getModel(string $modelName) : ModelInterface
    {
        $className = ucfirst($modelName) . 'Model';
        $fullName  = self::MODEL_NAMESPACE . "\\" . $className;

        try {
            $model = new $fullName;
        } catch (\Exception $e) {
            exit("Failed to instantiate Model: $className in ModuleProvider\n");
        }

        return $model;
    }
}
