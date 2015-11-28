<?php

namespace Assembly\Test\Container;

use Assembly\AliasDefinition;
use Assembly\Container\Container;
use Assembly\FactoryCallDefinition;
use Assembly\ObjectDefinition;
use Assembly\ParameterDefinition;
use Assembly\Reference;
use Assembly\Test\Container\Fixture\Class1;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function stores_and_returns_entries()
    {
        $container = new Container([
            'foo' => 'bar',
        ]);

        $this->assertTrue($container->has('foo'));
        $this->assertSame('bar', $container->get('foo'));
    }

    /**
     * @test
     */
    public function resolves_parameter_definitions()
    {
        $provider = new FakeDefinitionProvider([
            new ParameterDefinition('foo', 'bar'),
        ]);

        $container = new Container([], [$provider]);

        $this->assertTrue($container->has('foo'));
        $this->assertSame('bar', $container->get('foo'));
    }

    /**
     * @test
     */
    public function resolves_instance_definitions()
    {
        $definition = new ObjectDefinition('foo', 'Assembly\Test\Container\Fixture\Class1');
        $definition->addPropertyAssignment('publicField', 'public field');
        $definition->addConstructorArgument('constructor param1');
        $definition->addConstructorArgument('constructor param2');
        $definition->addMethodCall('setSomething', 'setter param1', 'setter param2');

        $provider = new FakeDefinitionProvider([
            $definition,
        ]);

        $container = new Container([], [$provider]);

        $this->assertTrue($container->has('foo'));
        /** @var Class1 $service */
        $service = $container->get('foo');
        $this->assertInstanceOf('Assembly\Test\Container\Fixture\Class1', $service);
        $this->assertSame('public field', $service->publicField);
        $this->assertSame('constructor param1', $service->constructorParam1);
        $this->assertSame('constructor param2', $service->constructorParam2);
        $this->assertSame('setter param1', $service->setterParam1);
        $this->assertSame('setter param2', $service->setterParam2);
    }

    /**
     * @test
     */
    public function resolves_references_in_instance_definitions()
    {
        $definition = new ObjectDefinition('foo', 'Assembly\Test\Container\Fixture\Class1');
        $definition->addPropertyAssignment('publicField', new Reference('ref1'));
        $definition->addConstructorArgument(new Reference('ref2'));
        $definition->addConstructorArgument(new Reference('ref3'));
        $definition->addMethodCall('setSomething', new Reference('ref4'), new Reference('ref5'));

        $provider = new FakeDefinitionProvider([
            $definition,
        ]);

        $container = new Container([
            'ref1' => 'public field',
            'ref2' => 'constructor param1',
            'ref3' => 'constructor param2',
            'ref4' => 'setter param1',
            'ref5' => 'setter param2',
        ], [$provider]);

        $this->assertTrue($container->has('foo'));
        /** @var Class1 $service */
        $service = $container->get('foo');
        $this->assertInstanceOf('Assembly\Test\Container\Fixture\Class1', $service);
        $this->assertSame('public field', $service->publicField);
        $this->assertSame('constructor param1', $service->constructorParam1);
        $this->assertSame('constructor param2', $service->constructorParam2);
        $this->assertSame('setter param1', $service->setterParam1);
        $this->assertSame('setter param2', $service->setterParam2);
    }

    /**
     * @test
     */
    public function resolves_instance_definitions_as_singleton()
    {
        $definition = new ObjectDefinition('foo', 'Assembly\Test\Container\Fixture\Class1');
        $definition->addConstructorArgument('param1');
        $definition->addConstructorArgument('param2');

        $provider = new FakeDefinitionProvider([
            $definition,
        ]);

        $container = new Container([], [$provider]);

        $this->assertSame($container->get('foo'), $container->get('foo'));
    }

    /**
     * @test
     */
    public function resolves_alias_definitions()
    {
        $provider = new FakeDefinitionProvider([
            new AliasDefinition('foo', 'bar'),
            new ParameterDefinition('bar', 'qux'),
        ]);

        $container = new Container([], [$provider]);

        $this->assertTrue($container->has('foo'));
        $this->assertSame('qux', $container->get('foo'));
    }

    /**
     * @test
     */
    public function resolves_service_factory_definitions()
    {
        $provider = new FakeDefinitionProvider([
            new FactoryCallDefinition('foo', new Reference('factory'), 'create'),
            new ObjectDefinition('factory', 'Assembly\Test\Container\Fixture\Factory'),
        ]);

        $container = new Container([], [$provider]);

        $this->assertTrue($container->has('foo'));
        $this->assertSame('Hello', $container->get('foo'));
    }

    /**
     * @test
     */
    public function resolves_static_factory_definitions()
    {
        $provider = new FakeDefinitionProvider([
            new FactoryCallDefinition('foo', 'Assembly\Test\Container\Fixture\Factory', 'staticCreate'),
        ]);

        $container = new Container([], [$provider]);

        $this->assertTrue($container->has('foo'));
        $this->assertSame('Hello', $container->get('foo'));
    }

    /**
     * @test
     */
    public function resolves_factory_instance_from_the_container()
    {
        $factoryInstance = new ObjectDefinition('factory', 'Assembly\Test\Container\Fixture\FactoryWithDependency');
        $factoryInstance->addConstructorArgument(new Reference('subFactory'));
        $provider = new FakeDefinitionProvider([
            new FactoryCallDefinition('foo', new Reference('factory'), 'create'),
            $factoryInstance,
            new ObjectDefinition('subFactory', 'Assembly\Test\Container\Fixture\Factory'),
        ]);

        $container = new Container([], [$provider]);

        $this->assertTrue($container->has('foo'));
        $this->assertSame('Hello', $container->get('foo'));
    }

    /**
     * @test
     */
    public function uses_the_provided_factory_arguments()
    {
        $provider = new FakeDefinitionProvider([
            (new FactoryCallDefinition('foo', new Reference('factory'), 'returnsRequestedId'))->setArguments('foobar'),
            new ObjectDefinition('factory', 'Assembly\Test\Container\Fixture\Factory'),
        ]);

        $container = new Container([], [$provider]);

        $this->assertTrue($container->has('foo'));
        $this->assertSame('foobar', $container->get('foo'));
    }
}
