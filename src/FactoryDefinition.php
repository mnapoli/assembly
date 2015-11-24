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

    public function getReference()
    {
        return $this->reference;
    }

    public function getMethodName()
    {
        return $this->methodName;
    }
}
