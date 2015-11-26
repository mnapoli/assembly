<?php

namespace Assembly;

use Interop\Container\Definition\FactoryDefinitionInterface;
use Interop\Container\Definition\ReferenceInterface;

class FactoryDefinition extends NamedDefinition implements FactoryDefinitionInterface
{
    /**
     * @var ReferenceInterface
     */
    private $reference;

    /**
     * @var string
     */
    private $methodName;

    /**
     * @var array
     */
    private $arguments;

    /**
     * @param string $identifier
     * @param ReferenceInterface $reference
     * @param string $methodName
     */
    public function __construct($identifier, ReferenceInterface $reference, $methodName)
    {
        parent::__construct($identifier);

        $this->reference = $reference;
        $this->methodName = $methodName;
    }

    /**
     * Set the arguments to pass when calling the factory.
     *
     * @param scalar|ReferenceInterface $argument Can be a scalar value or a reference to another entry.
     * @param scalar|ReferenceInterface ...
     *
     * @return $this
     */
    public function setArguments($argument)
    {
        $this->arguments = func_get_args();

        return $this;
    }

    public function getReference()
    {
        return $this->reference;
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
