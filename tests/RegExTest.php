<?php

namespace Datapp\Router;

use PHPUnit\Framework\TestCase;
use Datapp\Router\RegEx;
use InvalidArgumentException;

class RegExTest extends TestCase
{

    public function testInvalidRegExShouldThrowException()
    {
        $this->expectException(InvalidArgumentException::class);
        new RegEx('/');
    }
}
