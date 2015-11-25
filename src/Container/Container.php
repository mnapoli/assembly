<?php

namespace Assembly\Container;

use Assembly\InstanceDefinition;
use Interop\Container\ContainerInterface;
use Interop\Container\Definition\AliasDefinitionInterface;
use Interop\Container\Definition\DefinitionInterface;
use Interop\Container\Definition\DefinitionProviderInterface;
use Interop\Container\Definition\FactoryDefinitionInterface;
use Interop\Container\Definition\ParameterDefinitionInterface;
use Interop\Container\Definition\ReferenceInterface;

/**
 * Simple container that can resolve standard definitions.
 */
class Container implements ContainerInterface
{
    /**
     * @var DefinitionInterface[]
     */
    private $definitions = [];

    /**
     * @var array
     */
    private $entries = [];

    /**
     * Register a definition provider.
     */
    public function addProvider(DefinitionProviderInterface $definitionProvider)
    {
        foreach ($definitionProvider->getDefinitions() as $definition) {
            $this->definitions[$definition->getIdentifier()] = $definition;
        }
    }

    public function get($id)
    {
        if (array_key_exists($id, $this->entries)) {
            return $this->entries[$id];
        }

        if (!isset($this->definitions[$id])) {
            throw EntryNotFound::fromId($id);
        }

        $this->entries[$id] = $this->resolveDefinition($this->definitions[$id], $id);

        return $this->entries[$id];
    }

    public function has($id)
    {
        return isset($this->definitions[$id]) || array_key_exists($id, $this->entries);
    }

    /**
     * Set an entry in the container.
     *
     * @param string $id
     * @param mixed $entry
     */
    public function set($id, $entry)
    {
        $this->entries[$id] = $entry;
    }

    /**
     * Resolve a definition and return the resulting value.
     *
     * @param DefinitionInterface $definition
     * @return mixed
     * @throws UnsupportedDefinition
     * @throws EntryNotFound A dependency was not found.
     */
    private function resolveDefinition(DefinitionInterface $definition, $requestedId)
    {
        switch (true) {
            case $definition instanceof ParameterDefinitionInterface:
                return $definition->getValue();
            case $definition instanceof InstanceDefinition:
                $reflection = new \ReflectionClass($definition->getClassName());

                // Create the instance
                $constructorArguments = $definition->getConstructorArguments();
                $constructorArguments = array_map([$this, 'resolveReference'], $constructorArguments);
                $service = $reflection->newInstanceArgs($constructorArguments);

                // Set properties
                foreach ($definition->getPropertyAssignments() as $propertyAssignment) {
                    $propertyName = $propertyAssignment->getPropertyName();
                    $service->$propertyName = $this->resolveReference($propertyAssignment->getValue());
                }

                // Call methods
                foreach ($definition->getMethodCalls() as $methodCall) {
                    $methodArguments = $methodCall->getArguments();
                    $methodArguments = array_map([$this, 'resolveReference'], $methodArguments);
                    call_user_func_array([$service, $methodCall->getMethodName()], $methodArguments);
                }

                return $service;
            case $definition instanceof AliasDefinitionInterface:
                return $this->get($definition->getTarget());
            case $definition instanceof FactoryDefinitionInterface:
                $factory = $this->get($definition->getReference()->getTarget());
                $methodName = $definition->getMethodName();
                return $factory->$methodName($requestedId);
            default:
                throw UnsupportedDefinition::fromDefinition($definition);
        }
    }

    /**
     * Resolve a variable that can be a reference.
     *
     * @param ReferenceInterface|mixed $value
     * @return mixed
     * @throws EntryNotFound The dependency was not found.
     */
    private function resolveReference($value)
    {
        if ($value instanceof ReferenceInterface) {
            $value = $this->get($value->getTarget());
        }

        return $value;
    }
}
