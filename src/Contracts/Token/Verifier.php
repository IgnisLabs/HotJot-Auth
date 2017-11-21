<?php

namespace IgnisLabs\HotJot\Auth\Contracts\Token;

use IgnisLabs\HotJot\Token;

interface Verifier {

    /**
     * @param Token $token
     * @return bool
     */
    public function verify(Token $token) : bool;
}
