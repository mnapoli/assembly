<?php

namespace Assembly\Container;

use Interop\Container\ContainerInterface;
use Interop\Container\Definition\DefinitionInterface;
use Interop\Container\Definition\DefinitionProviderInterface;

/**
 * Simple immutable container that can resolve standard definitions.
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
     * @var DefinitionResolver
     */
    private $resolver;

    /**
     * @param array $entries Container entries.
     * @param DefinitionProviderInterface[] $providers
     */
    public function __construct(array $entries, array $providers = [])
    {
        $this->entries = $entries;
        foreach ($providers as $provider) {
            $this->definitions = array_merge($this->definitions, $provider->getDefinitions());
        }

        $this->resolver = new DefinitionResolver($this);
    }

    public function get($id)
    {
        if (array_key_exists($id, $this->entries)) {
            return $this->entries[$id];
        }

        if (!isset($this->definitions[$id])) {
            throw EntryNotFound::fromId($id);
        }

        $this->entries[$id] = $this->resolver->resolve($this->definitions[$id]);

        return $this->entries[$id];
    }

    public function has($id)
    {
        return isset($this->definitions[$id]) || array_key_exists($id, $this->entries);
    }
}
