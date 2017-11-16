<?php

namespace IgnisLabs\HotJot\Auth\Contracts\Token;

use IgnisLabs\HotJot\Token;

interface Refresher {

    /**
     * Get a new token based on a previous valid one
     * It validates and verifies the token. You can exclude certain validators from running when refreshing
     * @param Token $token
     * @param array $excludeValidators
     * @return Token
     */
    public function refresh(Token $token, ...$excludeValidators) : Token;
}
