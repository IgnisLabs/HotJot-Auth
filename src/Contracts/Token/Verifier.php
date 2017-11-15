<?php

namespace IgnisLabs\HotJot\Auth\Contracts\Token;

use IgnisLabs\HotJot\Token;
use IgnisLabs\HotJot\Auth\Exceptions\SignatureVerificationFailedException;

interface Verifier {

    /**
     * @param Token $token
     * @return bool
     */
    public function verify(Token $token) : bool;
}
