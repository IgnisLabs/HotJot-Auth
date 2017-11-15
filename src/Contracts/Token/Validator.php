<?php

namespace IgnisLabs\HotJot\Auth\Contracts\Token;

use IgnisLabs\HotJot\Auth\Contracts\Token;

interface Validator {

    /**
     * Validate a token
     * @param Token $token
     */
    public function validate(Token $token) : void;
}
