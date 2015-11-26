<?php

namespace Assembly\Test;

class FunctionsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function no_error_if_included_twice()
    {
        require __DIR__ . '/../src/functions.php';
        require __DIR__ . '/../src/functions.php';
    }
}
