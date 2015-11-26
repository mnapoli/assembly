<?php

namespace Assembly\Test;

use Assembly\FactoryDefinition;
use Assembly\Reference;

class FactoryDefinitionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function accepts_arguments()
    {
        $definition = new FactoryDefinition('id', new Reference('service'), 'method');
        $definition->setArguments('param1', 'param2');

        $this->assertSame(['param1', 'param2'], $definition->getArguments());
    }

    /**
     * @test
     */
    public function is_fluent()
    {
        $definition = new FactoryDefinition('id', new Reference('service'), 'method');

        $this->assertSame($definition, $definition->setArguments('param1'));
    }
}
