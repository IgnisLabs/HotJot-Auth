<?php

namespace IgnisLabs\HotJot\Contracts;

use IgnisLabs\HotJot\Contracts\Token;
use Symfony\Component\HttpFoundation\Request;

interface RequestParser {

    /**
     * Parse token from current request
     * @return Token
     */
    public function parse() : Token;
}
