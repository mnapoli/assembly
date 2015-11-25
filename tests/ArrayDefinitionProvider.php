<?php

namespace Assembly\Test;

use Interop\Container\Definition\DefinitionProviderInterface;

class ArrayDefinitionProvider implements DefinitionProviderInterface
{
    private $definitions;

    public function __construct(array $definitions)
    {
        $this->definitions = $definitions;
    }

    public function getDefinitions()
    {
        return $this->definitions;
    }
}
