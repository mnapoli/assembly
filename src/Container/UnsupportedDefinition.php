<?php

namespace Assembly\Container;

use Interop\Container\Exception\NotFoundException;

/**
 * The definition is not supported by the container.
 */
class UnsupportedDefinition extends \Exception implements NotFoundException
{
    public static function fromDefinition($definition)
    {
        return new self(sprintf('%s is not a supported definition', get_class($definition)));
    }
}
