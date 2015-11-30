<?php

namespace Assembly;

use Interop\Container\Definition\ReferenceDefinitionInterface;

class Reference implements ReferenceDefinitionInterface
{
    /**
     * @var string
     */
    private $target;

    /**
     * @param string $target
     */
    public function __construct($target)
    {
        $this->target = $target;
    }

    /**
     * Returns the name of the target container entry.
     *
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }
}
