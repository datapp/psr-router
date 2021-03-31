<?php

namespace Datapp\Router;

use Datapp\Router\Dispatcher;
use Datapp\Router\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class DispatcherTest extends TestCase
{

    public function testExceptionWillBeThrownIfNoRequestHandlerIsGiven()
    {
        $this->expectException(InvalidArgumentException::class);
        $dispatcher = new Dispatcher();
        $request = $this->createStub(ServerRequestInterface::class);
        $dispatcher->handle($request);
    }
}
