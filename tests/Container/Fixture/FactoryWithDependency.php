<?php

namespace Assembly\Test\Container\Fixture;

class FactoryWithDependency
{
    private $subFactory;

    public function __construct(Factory $subFactory)
    {
        $this->subFactory = $subFactory;
    }

    public function create()
    {
        return $this->subFactory->create();
    }
}
