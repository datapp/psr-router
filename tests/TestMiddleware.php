<?php

namespace Datapp\Router;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

class TestMiddleware implements MiddlewareInterface
{

    private $num;

    /** @var ResponseFactoryInterface */
    private $responseFactory;

    /** @var StreamFactoryInterface */
    private $streamFactory;

    public function __construct(ResponseFactoryInterface $responseFactory, StreamFactoryInterface $streamFactory, int $num)
    {
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
        $this->num = $num;
    }

    //put your code here
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        if (filter_var($queryParams['id'], FILTER_VALIDATE_INT) === 1) {
            return $this->responseFactory->createResponse(400)->withBody($this->streamFactory->createStream('Bad Request'));
        }
        $response = $handler->handle($request);
        $body = $response->getBody();
        $body->write(' #' . $this->num);
        return $response->withBody($body);
    }

}
