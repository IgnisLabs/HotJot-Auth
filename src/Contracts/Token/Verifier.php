<?php

namespace IgnisLabs\HotJot\Auth\Contracts\Token;

use IgnisLabs\HotJot\Auth\Contracts\Token;
use IgnisLabs\HotJot\Auth\Exceptions\SignatureVerificationFailedException;

interface Verifier {

    /**
     * @param Token $token
     * @throws SignatureVerificationFailedException
     */
    public function verify(Token $token);
}
