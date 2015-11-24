<?php

namespace Assembly;

use Interop\Container\Definition\PropertyAssignmentInterface;
use Interop\Container\Definition\ReferenceInterface;

class PropertyAssignment implements PropertyAssignmentInterface
{
    /**
     * @var string
     */
    private $propertyName;

    /**
     * @var scalar|ReferenceInterface
     */
    private $value;

    /**
     * @param string $propertyName
     * @param scalar|ReferenceInterface $value
     */
    public function __construct($propertyName, $value)
    {
        $this->propertyName = $propertyName;
        $this->value = $value;
    }

    public function getPropertyName()
    {
        return $this->propertyName;
    }

    public function getValue()
    {
        return $this->value;
    }
}
