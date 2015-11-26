<?php

namespace Assembly\Test\Container;

use Interop\Container\Definition\DefinitionProviderInterface;

class FakeDefinitionProvider implements DefinitionProviderInterface
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
