<?php

namespace Assembly;

use Interop\Container\Definition\ReferenceDefinitionInterface;

if (!function_exists('Assembly\object')) {

    /**
     * Create an instance definition.
     *
     * @param string $className
     *
     * @return ObjectDefinition
     */
    function object($className)
    {
        return new ObjectDefinition($className);
    }

    /**
     * Create a "factory call" definition.
     *
     * @param ReferenceDefinitionInterface|string $factory Reference to the service on which to call the method
     *                                           or fully qualified class name for static calls.
     * @param string $method Method to call on the service.
     *
     * @return FactoryCallDefinition
     */
    function factory($factory, $method)
    {
        return new FactoryCallDefinition($factory, $method);
    }

    /**
     * Create a reference to another container entry.
     *
     * @param string $identifier ID of the referenced container entry.
     *
     * @return Reference
     */
    function get($identifier)
    {
        return new Reference($identifier);
    }

}
