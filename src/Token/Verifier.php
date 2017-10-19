<?php

namespace IgnisLabs\HotJot\Token;

use IgnisLabs\HotJot\Contracts\Token;
use IgnisLabs\HotJot\Contracts\Token\Verifier as VerifierContract;
use IgnisLabs\HotJot\Exceptions\SignatureVerificationFailedException;
use Lcobucci\JWT\Signer;

class Verifier implements VerifierContract {

    /**
     * @var Signer
     */
    private $signer;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $publicKey;

    /**
     * Verifier constructor.
     * @param Signer      $signer
     * @param string      $key
     */
    public function __construct(Signer $signer, string $key) {
        $this->signer = $signer;
        $this->key = $key;
    }

    /**
     * @param Token $token
     * @throws SignatureVerificationFailedException
     */
    public function verify(Token $token) {
        if (!$this->signer->verify($token->signature(), (string) $token, $this->key)) {
            throw new SignatureVerificationFailedException;
        }
    }
}
