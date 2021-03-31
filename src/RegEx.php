<?php

namespace Datapp\Router;

use Datapp\Router\InvalidArgumentException;

/**
 * @author Manuel Dimmler
 */
class RegEx
{

    private string $regex;

    /**
     * @param string $regex
     * @throws InvalidArgumentException
     */
    public function __construct(string $regex)
    {
        $this->ensureRegExIsValid($regex);
        $this->regex = $regex;
    }

    /**
     * @param string $regex
     * @return void
     * @throws InvalidArgumentException
     */
    private function ensureRegExIsValid(string $regex): void
    {
        if (@preg_match($regex, '') === false) {
            throw InvalidArgumentException::regex($regex);
        }
    }

    public function toString(): string
    {
        return $this->regex;
    }
}
