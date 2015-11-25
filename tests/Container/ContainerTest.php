<?php

namespace Assembly\Test\Container;

use Assembly\AliasDefinition;
use Assembly\Container\Container;
use Assembly\FactoryDefinition;
use Assembly\InstanceDefinition;
use Assembly\MethodCall;
use Assembly\ParameterDefinition;
use Assembly\PropertyAssignment;
use Assembly\Reference;
use Assembly\Test\ArrayDefinitionProvider;
use Assembly\Test\Container\Fixture\Class1;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function stores_and_returns_entries()
    {
        $container = new Container();

        $container->set('foo', 'bar');

        $this->assertTrue($container->has('foo'));
        $this->assertSame('bar', $container->get('foo'));
    }

    /**
     * @test
     */
    public function resolves_parameter_definitions()
    {
        $provider = new ArrayDefinitionProvider([
            new ParameterDefinition('foo', 'bar'),
        ]);

        $container = new Container();
        $container->addProvider($provider);

        $this->assertTrue($container->has('foo'));
        $this->assertSame('bar', $container->get('foo'));
    }

    /**
     * @test
     */
    public function resolves_instance_definitions()
    {
        $definition = new InstanceDefinition('foo', 'Assembly\Test\Container\Fixture\Class1');
        $definition->addPropertyAssignment(new PropertyAssignment('publicField', 'public field'));
        $definition->addConstructorArgument('constructor param1');
        $definition->addConstructorArgument('constructor param2');
        $definition->addMethodCall(new MethodCall('setSomething', ['setter param1', 'setter param2']));

        $provider = new ArrayDefinitionProvider([
            $definition,
        ]);

        $container = new Container();
        $container->addProvider($provider);

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
        $definition = new InstanceDefinition('foo', 'Assembly\Test\Container\Fixture\Class1');
        $definition->addPropertyAssignment(new PropertyAssignment('publicField', new Reference('ref1')));
        $definition->addConstructorArgument(new Reference('ref2'));
        $definition->addConstructorArgument(new Reference('ref3'));
        $definition->addMethodCall(new MethodCall('setSomething', [new Reference('ref4'), new Reference('ref5')]));

        $provider = new ArrayDefinitionProvider([
            $definition,
        ]);

        $container = new Container();
        $container->addProvider($provider);
        $container->set('ref1', 'public field');
        $container->set('ref2', 'constructor param1');
        $container->set('ref3', 'constructor param2');
        $container->set('ref4', 'setter param1');
        $container->set('ref5', 'setter param2');

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
    public function resolves_alias_definitions()
    {
        $provider = new ArrayDefinitionProvider([
            new AliasDefinition('foo', 'bar'),
            new ParameterDefinition('bar', 'qux'),
        ]);

        $container = new Container();
        $container->addProvider($provider);

        $this->assertTrue($container->has('foo'));
        $this->assertSame('qux', $container->get('foo'));
    }

    /**
     * @test
     */
    public function resolves_factory_definitions()
    {
        $provider = new ArrayDefinitionProvider([
            new FactoryDefinition('foo', new Reference('factory'), 'create'),
            new InstanceDefinition('factory', 'Assembly\Test\Container\Fixture\Factory'),
        ]);

        $container = new Container();
        $container->addProvider($provider);

        $this->assertTrue($container->has('foo'));
        $this->assertSame('Hello', $container->get('foo'));
    }

    /**
     * @test
     */
    public function resolves_factory_instance_from_the_container()
    {
        $factoryInstance = new InstanceDefinition('factory', 'Assembly\Test\Container\Fixture\FactoryWithDependency');
        $factoryInstance->addConstructorArgument(new Reference('subFactory'));
        $provider = new ArrayDefinitionProvider([
            new FactoryDefinition('foo', new Reference('factory'), 'create'),
            $factoryInstance,
            new InstanceDefinition('subFactory', 'Assembly\Test\Container\Fixture\Factory'),
        ]);

        $container = new Container();
        $container->addProvider($provider);

        $this->assertTrue($container->has('foo'));
        $this->assertSame('Hello', $container->get('foo'));
    }

    /**
     * @test
     */
    public function passes_the_requested_entry_to_the_factory()
    {
        $provider = new ArrayDefinitionProvider([
            new FactoryDefinition('foo', new Reference('factory'), 'returnsRequestedId'),
            new InstanceDefinition('factory', 'Assembly\Test\Container\Fixture\Factory'),
        ]);

        $container = new Container();
        $container->addProvider($provider);

        $this->assertTrue($container->has('foo'));
        $this->assertSame('foo', $container->get('foo'));
    }
}
