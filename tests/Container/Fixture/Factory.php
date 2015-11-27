<?php

namespace Assembly\Test\Container\Fixture;

class Factory
{
    public function create()
    {
        return 'Hello';
    }

    public function returnsRequestedId($requestedId)
    {
        return $requestedId;
    }

    public static function staticCreate()
    {
        return 'Hello';
    }
}
