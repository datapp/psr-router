<?php

namespace Datapp\Router;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

class Route
{

    /** @var string */
    private $method;

    /** @var string */
    private $regex;

    /** @var RequestHandlerInterface */
    private $requestHandler;

    /** @var MiddlewareInterface[] */
    private $middlewares = [];

    public function __construct(string $method, string $regex, RequestHandlerInterface $requestHandler)
    {
        $this->method = $method;
        $this->regex = $regex;
        $this->requestHandler = $requestHandler;
    }

    public static function create(string $method, string $regex, RequestHandlerInterface $requestHandler)
    {
        return new self($method, $regex, $requestHandler);
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getRegex()
    {
        return $this->regex;
    }

    public function getRequestHandler(): RequestHandlerInterface
    {
        return $this->requestHandler;
    }

    public function addMiddleware(MiddlewareInterface ...$middlewares)
    {
        $this->middlewares = array_merge($this->middlewares, $middlewares);
    }

    /**
     * @return MiddlewareInterface[]
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

}
