<?php

namespace Assembly;

use Interop\Container\Definition\FactoryCallDefinitionInterface;
use Interop\Container\Definition\ReferenceDefinitionInterface;

class FactoryCallDefinition implements FactoryCallDefinitionInterface
{
    /**
     * @var ReferenceDefinitionInterface|string
     */
    private $factory;

    /**
     * @var string
     */
    private $methodName;

    /**
     * @var array
     */
    private $arguments = [];

    /**
     * @param ReferenceDefinitionInterface|string $factory A reference to the service being called or a fully qualified class name for static calls
     * @param string $methodName
     */
    public function __construct($factory, $methodName)
    {
        $this->factory = $factory;
        $this->methodName = $methodName;
    }

    /**
     * Set the arguments to pass when calling the factory.
     *
     * @param string|number|bool|array|ReferenceDefinitionInterface $argument Can be a scalar value or a reference to another entry.
     * @param string|number|bool|array|ReferenceDefinitionInterface ...
     *
     * @return $this
     */
    public function setArguments($argument)
    {
        $this->arguments = func_get_args();

        return $this;
    }

    public function getFactory()
    {
        return $this->factory;
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
