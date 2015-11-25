<?php

namespace Assembly\Container;

use Interop\Container\Exception\NotFoundException;

/**
 * A container entry was not found.
 */
class EntryNotFound extends \Exception implements NotFoundException
{
    public static function fromId($id)
    {
        return new self(sprintf('The container entry "%s" was not found', $id));
    }
}
