<?php

namespace IgnisLabs\HotJot\Auth\Contracts;

use IgnisLabs\HotJot\Auth\Contracts\Token;
use Symfony\Component\HttpFoundation\Request;

interface RequestParser {

    /**
     * Parse token from current request
     * @return Token
     */
    public function parse() : Token;
}
