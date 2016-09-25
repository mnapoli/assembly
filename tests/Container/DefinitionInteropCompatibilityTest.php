<?php

namespace Assembly\Test\Container;

use Assembly\Container\Container;
use Interop\Container\ContainerInterface;
use Interop\Container\Definition\DefinitionProviderInterface;
use Interop\Container\Definition\Test\AbstractDefinitionCompatibilityTest;

class DefinitionInteropCompatibilityTest extends AbstractDefinitionCompatibilityTest
{


    /**
     * Takes a definition provider in parameter and returns a container containing the entries.
     *
     * @param DefinitionProviderInterface $definitionProvider
     * @return ContainerInterface
     */
    protected function getContainer(DefinitionProviderInterface $definitionProvider)
    {
        return new Container([], [ $definitionProvider ]);
    }
}
