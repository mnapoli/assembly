<?php

namespace Assembly\Test;

use Assembly\AliasDefinition;
use Assembly\ArrayDefinitionProvider;
use Assembly\FactoryCallDefinition;
use Assembly\ObjectDefinition;
use Assembly\ParameterDefinition;
use Assembly\Reference;
use Interop\Container\Definition\DefinitionInterface;

class ArrayDefinitionProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function provide_definitions_from_array()
    {
        $provider = new ArrayDefinitionProvider([
            'parameter' => 'Hello world',

            'instance' => \Assembly\object('Assembly\Test\Container\Fixture\Class1')
                ->setConstructorArguments('param1', \Assembly\get('parameter'))
                ->addPropertyAssignment('publicField', 'value1')
                ->addMethodCall('setSomething', 'param1', \Assembly\get('parameter')),

            'factory.service' => \Assembly\object('Assembly\Test\Container\Fixture\Factory'),
            'factory' => \Assembly\factory(\Assembly\get('factory.service'), 'create')
                ->setArguments('param1'),

            'static_factory' => \Assembly\factory('Assembly\Test\Container\Fixture\Factory', 'staticCreate'),

            'alias' => \Assembly\alias('parameter'),
        ]);

        /** @var DefinitionInterface[] $definitions */
        $definitions = $provider->getDefinitions();

        $this->assertCount(6, $definitions);

        $this->assertEquals(new ParameterDefinition('parameter', 'Hello world'), $definitions[0]);

        $expectedInstance = new ObjectDefinition('instance', 'Assembly\Test\Container\Fixture\Class1');
        $expectedInstance->setConstructorArguments('param1', new Reference('parameter'));
        $expectedInstance->addPropertyAssignment('publicField', 'value1');
        $expectedInstance->addMethodCall('setSomething', 'param1', new Reference('parameter'));
        $this->assertEquals($expectedInstance, $definitions[1]);

        $this->assertEquals(new ObjectDefinition('factory.service', 'Assembly\Test\Container\Fixture\Factory'), $definitions[2]);
        $expectedFactory = new FactoryCallDefinition('factory', new Reference('factory.service'), 'create');
        $expectedFactory->setArguments('param1');
        $this->assertEquals($expectedFactory, $definitions[3]);
        $expectedFactory = new FactoryCallDefinition('static_factory', 'Assembly\Test\Container\Fixture\Factory', 'staticCreate');
        $this->assertEquals($expectedFactory, $definitions[4]);

        $this->assertEquals(new AliasDefinition('alias', 'parameter'), $definitions[5]);
    }
}
