<?php

namespace Assembly;

use Interop\Container\Definition\ReferenceInterface;

class Reference implements ReferenceInterface
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
