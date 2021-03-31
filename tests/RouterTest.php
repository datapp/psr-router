<?php

declare(strict_types=1);

namespace Datapp\Router;

use Datapp\Router\Dispatcher;
use Datapp\Router\Method;
use Datapp\Router\Mock\TestHandler;
use Datapp\Router\Mock\TestMiddleware;
use Datapp\Router\RegEx;
use Datapp\Router\RouteNotFoundException;
use Datapp\Router\Router;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @author Manuel Dimmler
 */
class RouterTest extends TestCase
{

    private function createResponseMock(int $statusCode): ResponseInterface
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->any())
                ->method('getStatusCode')
                ->willReturn($statusCode);
        return $response;
    }

    public function testShouldReturnNotFoundIfMethodIsNotAvailable(): void
    {
        $this->expectException(RouteNotFoundException::class);
        $router = new Router(new Dispatcher());
        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->any())
                ->method('getMethod')
                ->willReturn('GET');
        $response = $router->route($request);
    }

    public function testShouldReturnNotFoundIfRoutesAreNotMatching(): void
    {
        $this->expectException(RouteNotFoundException::class);
        $router = new Router(new Dispatcher());
        $uri = $this->createMock(UriInterface::class);
        $uri->expects($this->any())
                ->method('getPath')
                ->willReturn('nonexisting-path');
        $requestHandler = $this->createStub(RequestHandlerInterface::class);
        $router->addRoute(Route::create('GET', '/^(test)$/', $requestHandler));
        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->any())
                ->method('getMethod')
                ->willReturn('GET');
        $request->expects($this->any())
                ->method('getUri')
                ->willReturn($uri);
        $response = $router->route($request);
    }

    public function testShouldReturnMatchingRoute(): void
    {
        // mock request
        $uri = $this->createMock(UriInterface::class);
        $uri->expects($this->any())
                ->method('getPath')
                ->willReturn('/test/me');
        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->any())
                ->method('getMethod')
                ->willReturn('GET');
        $request->expects($this->any())
                ->method('getUri')
                ->willReturn($uri);
        $request->expects($this->any())
                ->method('getQueryParams')
                ->willReturn(['foo' => 'bar']);
        // test request having original query params plus uri parts
        $request->expects($this->any())
                ->method('withQueryParams')
                ->with($this->equalTo(['foo' => 'bar', 0 => 'test/me', 1 => 'test', 2 => 'me']))
                ->willReturnSelf();
        // create route 1 (not matching)
        $requestHandler1 = $this->createMock(RequestHandlerInterface::class);
        $requestHandler1
                ->expects($this->never())
                ->method('handle');
        $route1 = Route::create('GET', '/^(test)$/', $requestHandler1);

        // create route 2 (matching)
        $requestHandler2 = $this->createMock(RequestHandlerInterface::class);
        $requestHandler2
                ->expects($this->once())
                ->method('handle')
                ->with($this->isInstanceOf(ServerRequestInterface::class))
                ->willReturn($this->createResponseMock(200));
        $route2 = new Route(new Method('GET'), new RegEx('/^(test)\/(me)$/'), $requestHandler2);
        $router = new Router(new Dispatcher());
        $router->addRoute($route1);
        $router->addRoute($route2);
        $router->route($request);
    }

    public function testShouldUseMiddlewares(): void
    {
        $responseFactory = $streamFactory = new Psr17Factory();
        $route = Route::create('GET', '/^(test)\/(?<count>[\d]+)$/', new TestHandler($responseFactory, $streamFactory))
                ->withMiddleware(new TestMiddleware(1), new TestMiddleware(2));
        $router = new Router(new Dispatcher());
        $router->addRoute($route);
        $request = new ServerRequest('GET', '/test/0');
        $response = $router->route($request);
        $body = $response->getBody();
        $body->rewind();
        $this->assertEquals('count: 2' . PHP_EOL . 'middleware: 2' . PHP_EOL . 'middleware: 1' . PHP_EOL, $body->getContents());
    }
}
