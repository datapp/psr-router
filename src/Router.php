<?php

declare(strict_types=1);

namespace Datapp\Router;

use Datapp\Router\Dispatcher;
use Datapp\Router\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @author Manuel Dimmler
 */
class Router
{

    /** @var array<string, array<Route>> */
    private array $routes = [];
    private Dispatcher $dispatcher;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function addRoute(Route $route): Route
    {
        if (!array_key_exists($route->getMethod()->toString(), $this->routes)) {
            $this->routes[$route->getMethod()->toString()] = [];
        }
        $this->routes[$route->getMethod()->toString()][] = $route;
        return $route;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws RouteNotFoundException
     */
    public function route(ServerRequestInterface $request): ResponseInterface
    {
        if (!array_key_exists($request->getMethod(), $this->routes)) {
            throw new RouteNotFoundException();
        }
        $matches = [];
        /** @var Route $route */
        foreach ($this->routes[$request->getMethod()] as $route) {
            if (preg_match($route->getRegex()->toString(), trim($request->getUri()->getPath(), '/'), $matches)) {
                $queryParams = array_merge($request->getQueryParams(), $matches);
                return $this->dispatcher
                                ->withMiddlewares(...$route->getMiddlewares())
                                ->withRequestHandler($route->getRequestHandler())
                                ->handle($request->withQueryParams($queryParams));
            }
        }
        throw new RouteNotFoundException();
    }
}
