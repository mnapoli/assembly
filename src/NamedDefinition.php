<?php

namespace Assembly;

use Interop\Container\Definition\DefinitionInterface;

abstract class NamedDefinition implements DefinitionInterface
{
    /**
     * @var string
     */
    private $identifier;

    /**
     * @param string $identifier
     */
    public function __construct($identifier)
    {
        $this->identifier = $identifier;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function createReference()
    {
        return new Reference($this->getIdentifier());
    }
}
