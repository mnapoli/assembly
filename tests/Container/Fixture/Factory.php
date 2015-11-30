<?php

namespace Assembly\Test\Container\Fixture;

class Factory
{
    public function create()
    {
        return 'Hello';
    }

    public function returnsParameters($param1, $param2)
    {
        return $param1 . $param2;
    }

    public static function staticCreate()
    {
        return 'Hello';
    }
}
