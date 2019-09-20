<?php

namespace Datapp\Router;

use Datapp\Router\Dispatcher;
use Psr\Http\Server\RequestHandlerInterface;

class DispatcherFactory
{

    public function create(RequestHandlerInterface $handler, array $middlewares = [])
    {
        return new Dispatcher($handler, $middlewares);
    }

}
