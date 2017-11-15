<?php

namespace IgnisLabs\HotJot\Auth\Contracts;

use IgnisLabs\HotJot\Token;

interface RequestParser {

    /**
     * Parse token from current request
     * @return Token
     */
    public function parse() : Token;
}
