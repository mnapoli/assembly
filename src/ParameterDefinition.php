<?php

namespace Assembly;

use Interop\Container\Definition\ParameterDefinitionInterface;

class ParameterDefinition implements ParameterDefinitionInterface
{
    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}
