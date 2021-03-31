<?php

namespace Datapp\Router;

use PHPUnit\Framework\TestCase;
use Datapp\Router\Method;
use InvalidArgumentException;

class MethodTest extends TestCase
{

    public function testInvalidRegExShouldThrowException()
    {
        $this->expectException(InvalidArgumentException::class);
        new Method('GRT');
    }
}
