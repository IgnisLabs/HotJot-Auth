<?php

namespace IgnisLabs\HotJot\Contracts\Token;

use IgnisLabs\HotJot\Contracts\Token;

interface Validator {

    /**
     * Validate a token
     * @param Token $token
     */
    public function validate(Token $token) : void;
}
