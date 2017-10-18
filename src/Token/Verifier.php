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
    private $privateKey;

    /**
     * @var string
     */
    private $publicKey;

    /**
     * Verifier constructor.
     * @param Signer      $signer
     * @param string      $privateKey
     * @param string|null $publicKey
     */
    public function __construct(Signer $signer, string $privateKey, string $publicKey = null) {
        $this->signer = $signer;
        $this->privateKey = $privateKey;
        $this->publicKey = $publicKey;
    }

    /**
     * @param Token $token
     * @throws SignatureVerificationFailedException
     */
    public function verify(Token $token) {
        if (!$this->signer->verify($token->signature(), (string) $token, $this->publicKey ?? $this->privateKey)) {
            throw new SignatureVerificationFailedException;
        }
    }
}
