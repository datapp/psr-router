<?php

namespace Datapp\Router;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Manuel Dimmler
 */
class Handler implements RequestHandlerInterface
{

    /**
     * @var array
     */
    private $middleware;

    /**
     * @var RequestHandlerInterface
     */
    private $handler;

    /**
     * @var integer
     */
    private $index = 0;

    /**
     * @param MiddlewareInterface[] $middleware
     * @param RequestHandlerInterface $handler
     */
    public function __construct(array $middleware, RequestHandlerInterface $handler)
    {
        $this->middleware = $middleware;
        $this->handler = $handler;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (empty($this->middleware[$this->index])) {
            return $this->handler->handle($request);
        }

        return $this->middleware[$this->index]->process($request, $this->nextHandler());
    }

    /**
     * Get a handler pointing to the next middleware.
     *
     * @return static
     */
    private function nextHandler()
    {
        $copy = clone $this;
        $copy->index++;

        return $copy;
    }

}
