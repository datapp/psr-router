<?php

namespace Datapp\Router\Mock;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;

class TestMiddleware implements MiddlewareInterface
{

    private $num;

    public function __construct(int $num)
    {
        $this->num = $num;
    }

    //put your code here
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $queryParams['count']++;
        $response = $handler->handle($request->withQueryParams($queryParams));
        $body = $response->getBody();
        $body->write('middleware: ' . $this->num . PHP_EOL);
        return $response->withBody($body);
    }
}
