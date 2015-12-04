<?php

namespace Assembly\Test\Container;

use Assembly\Container\Container;
use Assembly\ObjectInitializer\MethodCall;
use Assembly\ObjectInitializer\PropertyAssignment;
use Assembly\Reference;
use Interop\Container\ContainerInterface;
use Interop\Container\Definition\DefinitionProviderInterface;
use Interop\Container\Definition\Test\AbstractDefinitionCompatibilityTest;
use Mouf\Picotainer\Picotainer;
use TheCodingMachine\Yaco\Definition\AbstractDefinitionTest;

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
