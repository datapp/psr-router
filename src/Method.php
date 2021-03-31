<?php

declare(strict_types=1);

namespace Datapp\Router;

use Datapp\Router\InvalidArgumentException;

/**
 * @author Manuel Dimmler
 */
class Method
{

    /**
     * The GET method requests a representation of the specified resource.
     * Requests using GET should only retrieve data.
     */
    const GET = 'GET';

    /**
     * The GET method requests a representation of the specified resource.
     * Requests using GET should only retrieve data.
     */
    const HEAD = 'HEAD';

    /**
     * The POST method is used to submit an entity to the specified resource,
     * often causing a change in state or side effects on the server.
     */
    const POST = 'POST';

    /**
     * The PUT method replaces all current representations of the target
     * resource with the request payload.
     */
    const PUT = 'PUT';

    /**
     * The DELETE method deletes the specified resource.
     */
    const DELETE = 'DELETE';

    /**
     * The OPTIONS method is used to describe the communication options for the
     * target resource.
     */
    const OPTIONS = 'OPTIONS';

    /**
     * The PATCH method is used to apply partial modifications to a resource.
     */
    const PATCH = 'PATCH';

    private string $method;

    /**
     * @param string $method
     * @throws InvalidArgumentException
     */
    public function __construct(string $method)
    {
        $this->ensureMethodIsValid($method);
        $this->method = $method;
    }

    /**
     * @param string $method
     * @return void
     * @throws InvalidArgumentException
     */
    private function ensureMethodIsValid(string $method): void
    {
        if (!in_array($method, $this->getValidMethodList(), true)) {
            throw InvalidArgumentException::method($method);
        }
    }

    private function getValidMethodList(): array
    {
        return [
            self::GET,
            self::HEAD,
            self::POST,
            self::PUT,
            self::DELETE,
            self::OPTIONS,
            self::PATCH,
        ];
    }

    public function toString(): string
    {
        return $this->method;
    }
}
