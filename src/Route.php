<?php

declare(strict_types=1);

namespace Datapp\Router;

use Datapp\Router\Method;
use Datapp\Router\RegEx;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Route
{

    private Method $method;
    private RegEx $regex;
    private RequestHandlerInterface $requestHandler;

    /** @var array<MiddlewareInterface> */
    private array $middlewares = [];

    public function __construct(Method $method, RegEx $regex, RequestHandlerInterface $requestHandler)
    {
        $this->method = $method;
        $this->regex = $regex;
        $this->requestHandler = $requestHandler;
    }

    public static function create(string $method, string $regex, RequestHandlerInterface $requestHandler): self
    {
        return new self(new Method($method), new RegEx($regex), $requestHandler);
    }

    public function getMethod(): Method
    {
        return $this->method;
    }

    public function getRegex(): RegEx
    {
        return $this->regex;
    }

    public function withMiddleware(MiddlewareInterface ...$middlewares): self
    {
        $clone = clone $this;
        $clone->middlewares = array_merge($this->middlewares, $middlewares);
        return $clone;
    }

    /**
     * @return array<MiddlewareInterface>
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    public function getRequestHandler(): RequestHandlerInterface
    {
        return $this->requestHandler;
    }
}
