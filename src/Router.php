<?php

namespace Datapp\Router;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Datapp\Router\DispatcherFactory;

/**
 * @author Manuel Dimmler
 */
class Router
{

    /** @var array */
    private $routes = [];

    /** @var DispatcherFactory */
    private $dispatcherFactory;

    /** @var RequestHandlerInterface */
    private $notFoundHandler;

    public function __construct(DispatcherFactory $dispatcherFactory, RequestHandlerInterface $notFoundHandler)
    {

        $this->dispatcherFactory = $dispatcherFactory;
        $this->notFoundHandler = $notFoundHandler;
    }

    public function addRoute(Route $route): Route
    {
        if (!array_key_exists($route->getMethod(), $this->routes)) {
            $this->routes[$route->getMethod()] = [];
        }
        $this->routes[$route->getMethod()][] = $route;
        return $route;
    }

    public function route(ServerRequestInterface $request): ResponseInterface
    {
        if (!array_key_exists($request->getMethod(), $this->routes)) {
            return $this->dispatcherFactory->create($this->notFoundHandler)->handle($request);
        }
        $matches = [];
        /** @var Route $route */
        foreach ($this->routes[$request->getMethod()] as $route) {
            if (preg_match($route->getRegex(), trim($request->getUri()->getPath(), '/'), $matches)) {
                $queryParams = array_merge($request->getQueryParams(), $matches);
                return $this->dispatcherFactory
                                ->create($route->getRequestHandler(), $route->getMiddlewares())
                                ->handle($request->withQueryParams($queryParams));
            }
        }
        return $this->dispatcherFactory->create($this->notFoundHandler)->handle($request);
    }

}
