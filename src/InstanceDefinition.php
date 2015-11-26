<?php

namespace Assembly;

use Interop\Container\Definition\InstanceDefinitionInterface;
use Interop\Container\Definition\MethodCallInterface;
use Interop\Container\Definition\PropertyAssignmentInterface;
use Interop\Container\Definition\ReferenceInterface;

class InstanceDefinition extends NamedDefinition implements InstanceDefinitionInterface
{
    /**
     * @var string
     */
    private $className;

    /**
     * @var array
     */
    private $constructorArguments = [];

    /**
     * @var PropertyAssignmentInterface[]
     */
    private $propertyAssignments = [];

    /**
     * @var MethodCallInterface[]
     */
    private $methodCalls = [];

    /**
     * @param string $identifier
     * @param string $className
     */
    public function __construct($identifier, $className)
    {
        parent::__construct($identifier);

        $this->className = $className;
    }

    /**
     * @param scalar|ReferenceInterface $argument
     *
     * @return $this
     */
    public function addConstructorArgument($argument)
    {
        $this->constructorArguments[] = $argument;

        return $this;
    }

    /**
     * Set constructor arguments. This method take as many parameters as necessary.
     *
     * @param scalar|ReferenceInterface $argument Can be a scalar value or a reference to another entry.
     * @param scalar|ReferenceInterface ...
     *
     * @return $this
     */
    public function setConstructorArguments($argument)
    {
        $this->constructorArguments = func_get_args();

        return $this;
    }

    /**
     * Set a value to assign to a property.
     *
     * @param string $propertyName Name of the property to set.
     * @param scalar|ReferenceInterface $value Can be a scalar value or a reference to another entry.
     *
     * @return $this
     */
    public function addPropertyAssignment($propertyName, $value)
    {
        $this->propertyAssignments[] = new PropertyAssignment($propertyName, $value);

        return $this;
    }

    /**
     * Set a method to be called after instantiating the class.
     *
     * After the $methodName parameter, this method take as many parameters as necessary.
     *
     * @param string $methodName Name of the method to call.
     * @param string|number|bool|array|ReferenceInterface... Can be a scalar value, an array of scalar or
     * a reference to another entry. See \Assembly\MethodCall::__construct fore more informations.
     *
     * @return $this
     */
    public function addMethodCall($methodName)
    {
        $arguments = func_get_args();
        array_shift($arguments);

        $this->methodCalls[] = new MethodCall($methodName, $arguments);

        return $this;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @return array
     */
    public function getConstructorArguments()
    {
        return $this->constructorArguments;
    }

    /**
     * @return PropertyAssignmentInterface[]
     */
    public function getPropertyAssignments()
    {
        return $this->propertyAssignments;
    }

    /**
     * @return MethodCallInterface[]
     */
    public function getMethodCalls()
    {
        return $this->methodCalls;
    }
}
