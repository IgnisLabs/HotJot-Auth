<?php

namespace IgnisLabs\HotJot\Contracts;

use IgnisLabs\HotJot\Contracts\Token;

interface Blacklist {

    /**
     * Add JWT to blacklist until it's expiration or indefinitely if it has no expiration date
     * @param Token $token
     */
    public function add(Token $token);

    /**
     * Check if token is blacklisted
     * @param string $jti
     * @return bool
     */
    public function has(string $jti) : bool;
}
