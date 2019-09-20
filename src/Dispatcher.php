<?php

namespace Datapp\Router;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Datapp\Router\Handler;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Manuel Dimmler
 */
class Dispatcher implements RequestHandlerInterface
{

    /** @var RequestHandlerInterface */
    private $handler;

    /** @var MiddlewareInterface[] */
    private $middlewares = [];

    public function __construct(RequestHandlerInterface $handler, $middlewares = [])
    {
        $this->handler = $handler;
        $this->middlewares = $middlewares;
    }

    public function withMiddleware(MiddlewareInterface ...$middlewares): self
    {
        return new self($this->handler, array_merge($this->middlewares, $middlewares));
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $handler = new Handler($this->middlewares, $this->handler);
        return $handler->handle($request);
    }

}
