<?php

namespace IgnisLabs\HotJot;

use Carbon\Carbon;
use Lcobucci\JWT\Token as LcobucciToken;
use IgnisLabs\HotJot\Contracts\Token\Token as TokenContract;

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
     * @return string|int
     */
    public function subject() {
        return $this->getClaim('sub');
    }

    /**
     * @return string
     */
    public function issuedBy() : string {
        return $this->getClaim('iss');
    }

    /**
     * @return string
     */
    public function audience() : string {
        return $this->getClaim('aud');
    }

    /**
     * @return \DateTimeInterface
     */
    public function issuedAt() : \DateTimeInterface {
        return $this->getClaimAsDateTime('iat');
    }

    /**
     * @return \DateTimeInterface
     */
    public function expiresAt() : \DateTimeInterface {
        return $this->getClaimAsDateTime('exp');
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
