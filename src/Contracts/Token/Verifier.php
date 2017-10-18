<?php

namespace IgnisLabs\HotJot\Contracts\Token;

use IgnisLabs\HotJot\Contracts\Token;
use IgnisLabs\HotJot\Exceptions\SignatureVerificationFailedException;

interface Verifier {

    /**
     * @param Token $token
     * @throws SignatureVerificationFailedException
     */
    public function verify(Token $token);
}
