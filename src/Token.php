<?php

namespace IgnisLabs\HotJot;

use Carbon\Carbon;
use Lcobucci\JWT\Token as LcobucciToken;
use IgnisLabs\HotJot\Contracts\Token as TokenContract;

class Token implements TokenContract {

    /**
     * @var LcobucciToken
     */
    private $token;

    /**
     * LcobucciToken constructor.
     * @param LcobucciToken $token
     */
    public function __construct(LcobucciToken $token) {
        $this->token = $token;
    }

    /**
     * @return string|int
     */
    public function id() {
        return $this->getClaim('jti');
    }

    /**
     * @return int|string|null
     */
    public function subject() {
        try {
            return $this->getClaim('sub');
        } catch (\OutOfBoundsException $exception) {
            return null;
        }
    }

    /**
     * @return string|null
     */
    public function issuedBy() : ?string {
        try {
            return $this->getClaim('iss');
        } catch (\OutOfBoundsException $exception) {
            return null;
        }
    }

    /**
     * @return string|null
     */
    public function audience() : ?string {
        try {
            return $this->getClaim('aud');
        } catch (\OutOfBoundsException $exception) {
            return null;
        }
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function issuedAt() : ?\DateTimeInterface {
        try {
            return $this->getClaimAsDateTime('iat');
        } catch (\OutOfBoundsException $exception) {
            return null;
        }
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function notBefore() : ?\DateTimeInterface {
        try {
            return $this->getClaimAsDateTime('nbf');
        } catch (\OutOfBoundsException $exception) {
            return null;
        }
    }

    /**
     * @return \DateTimeInterface
     */
    public function expiresAt() : \DateTimeInterface {
        return $this->getClaimAsDateTime('exp');
    }

    /**
     * Get token signature
     * @return null|string
     */
    public function signature() : ?string {
        $tokenParts = explode('.', $this->token->__toString());
        return $tokenParts[2] ?? null;
    }

    /**
     * Get claim value
     * @param $name
     * @return mixed
     */
    public function getClaim($name) {
        return $this->token->getClaim($name);
    }

    /**
     * Get all claims
     * @return array
     */
    public function getClaims() : array {
        return $this->token->getClaims();
    }

    /**
     * Get header value
     * @param $name
     * @return mixed
     */
    public function getHeader($name) {
        return $this->token->getHeader($name);
    }

    /**
     * Get all headers
     * @return array
     */
    public function getHeaders() : array {
        return $this->token->getHeaders();
    }

    /**
     * Convert to string
     * @return string
     */
    public function __toString() {
        return $this->token->__toString();
    }

    /**
     * Get a claim value as a date (implemented in Carbon)
     * @param $name
     * @return \DateTimeInterface
     */
    private function getClaimAsDateTime($name) : \DateTimeInterface {
        return Carbon::createFromTimestamp($this->getClaim($name));
    }
}
