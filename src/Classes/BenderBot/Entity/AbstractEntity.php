<?php

namespace BenderBot\Entity;

class AbstractEntity
{
    // Hydrate entity
    public function __construct(array $values)
    {
        foreach($values as $property => $value) {
            $property = $this->camelify($property);
            if(property_exists($this, $property)) {
                if(!strstr('date', $property)) {
                    $this->{$property} = $value;
                } else {
                    $this->{$property} = \DateTime::createFromFormat('Y-m-d H:m:s');
                }
            } else {
                exit("Error while hydrating " . self::classname . " property: $property does not exist");
            }
        }
    }

    // Transform snake_case to camel.
    private function camelify(string $property) : string
    {
        if(false !== strpos($property, '_')) {
            $letters = str_split($property);
            for($i = 0; $i < count($letters); $i++) {
                if($letters[$i] === '_') {
                    $letters[$i + 1] = strtoupper($letters[$i + 1]);
                    $letters[$i] = null;
                }
            }
            $property = implode('', $letters);
        }

        return $property;
    }
}
