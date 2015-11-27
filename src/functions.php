<?php

namespace Assembly;

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
        return new ObjectDefinition(null, $className);
    }

    /**
     * Create a "factory call" definition.
     *
     * @param string $service ID of the service on which to call the method.
     * @param string $method Method to call on the service.
     *
     * @return FactoryCallDefinition
     */
    function factory($service, $method)
    {
        return new FactoryCallDefinition(null, new Reference($service), $method);
    }

    /**
     * Create an alias definition that aliases a container entry to another.
     *
     * @param string $target
     *
     * @return AliasDefinition
     */
    function alias($target)
    {
        return new AliasDefinition(null, $target);
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
