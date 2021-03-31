<?php

declare(strict_types=1);

namespace Datapp\Router;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Manuel Dimmler
 */
class Dispatcher implements RequestHandlerInterface
{

    /** @var array<MiddlewareInterface> */
    private array $middlewares = [];
    private ?RequestHandlerInterface $handler = null;
    private int $index = 0;

    public function withMiddlewares(MiddlewareInterface ...$middlewares): self
    {
        $clone = clone $this;
        $clone->middlewares = $middlewares;
        return $clone;
    }

    public function withRequestHandler(RequestHandlerInterface $handler): self
    {
        $clone = clone $this;
        $clone->handler = $handler;
        return $clone;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (empty($this->middlewares[$this->index])) {
            return $this->handler->handle($request);
        }

        return $this->middlewares[$this->index]->process($request, $this->nextHandler());
    }

    /**
     * Get a handler pointing to the next middleware.
     *
     * @return self
     */
    private function nextHandler(): self
    {
        $copy = clone $this;
        $copy->index++;

        return $copy;
    }
}
