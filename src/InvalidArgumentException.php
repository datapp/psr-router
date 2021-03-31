<?php

declare(strict_types=1);

namespace Datapp\Router;

/**
 * @author Manuel Dimmler
 */
final class InvalidArgumentException extends \InvalidArgumentException
{

    const METHOD = 1;
    const REGEX = 2;
    const MISSING_REQUEST_HANDLER = 3;

    public static function method(string $method): self
    {
        return new self(sprintf('"%s" is not a valid method', $method), self::METHOD);
    }

    public static function regex(string $regex): self
    {
        return new self(sprintf('"%s" is not a valid regular expression', $regex), self::REGEX);
    }

    public static function missingRequestHandler(): self
    {
        return new self('request handler is missing', self::MISSING_REQUEST_HANDLER);
    }
}
