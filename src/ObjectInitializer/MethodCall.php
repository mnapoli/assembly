<?php

namespace Assembly\ObjectInitializer;

use Interop\Container\Definition\ObjectInitializer\MethodCallInterface;
use Interop\Container\Definition\ReferenceDefinitionInterface;

class MethodCall implements MethodCallInterface
{
    /**
     * @var string
     */
    private $methodName;

    /**
     * @var mixed[]|ReferenceDefinitionInterface[] Array of scalar or ReferenceDefinitionInterface
     */
    private $arguments = [];

    /**
     * @param string $methodName
     * @param array $arguments Array of scalar or ReferenceDefinitionInterface
     */
    public function __construct($methodName, array $arguments)
    {
        $this->methodName = $methodName;
        $this->arguments = $arguments;
    }

    public function getMethodName()
    {
        return $this->methodName;
    }

    public function getArguments()
    {
        return $this->arguments;
    }
}
