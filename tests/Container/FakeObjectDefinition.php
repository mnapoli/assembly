<?php

namespace Assembly\Test\Container;

use Interop\Container\Definition\ObjectDefinitionInterface;

class FakeObjectDefinition implements ObjectDefinitionInterface
{
    protected $className;
    
    public function __construct($className)
    {
        $this->className = $className;
    }
    
    public function getClassName()
    {
        return $this->className;
    }

    public function getConstructorArguments()
    {
        return [];
    }

    public function getMethodCalls()
    {
        return [];
    }

    public function getPropertyAssignments()
    {
        return [];
    }
}
