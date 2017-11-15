<?php

namespace IgnisLabs\HotJot\Auth\Token;

use IgnisLabs\HotJot\Token;
use IgnisLabs\HotJot\Contracts\Signer;
use IgnisLabs\HotJot\Auth\Contracts\Token\Verifier as VerifierContract;

class Verifier implements VerifierContract {

    /**
     * @var Signer
     */
    private $signer;

    /**
     * Verifier constructor.
     * @param Signer $signer
     */
    public function __construct(Signer $signer) {
        $this->signer = $signer;
    }

    /**
     * @param Token $token
     * @return bool
     */
    public function verify(Token $token) : bool {
        return $this->signer->verify($token);
    }
}
