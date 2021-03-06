<?php

namespace IgnisLabs\HotJot\Contracts;

interface Token {

    /**
     * @return int|string
     */
    public function id();

    /**
     * @return int|string|null
     */
    public function subject();

    /**
     * @return string|null
     */
    public function issuedBy() : ?string;

    /**
     * @return string|null
     */
    public function audience() : ?string;

    /**
     * @return \DateTimeInterface|null
     */
    public function issuedAt() : ?\DateTimeInterface;

    /**
     * @return \DateTimeInterface|null
     */
    public function notBefore() : ?\DateTimeInterface;

    /**
     * @return \DateTimeInterface
     */
    public function expiresAt() : \DateTimeInterface;

    /**
     * Get token signature
     * @return null|string
     */
    public function signature() : ?string;

    /**
     * Get claim value
     * @param $name
     * @return mixed
     */
    public function getClaim($name);

    /**
     * Get all claims
     * @return array
     */
    public function getClaims() : array;

    /**
     * Get header value
     * @param $name
     * @return mixed
     */
    public function getHeader($name);

    /**
     * Get all headers
     * @return array
     */
    public function getHeaders() : array;

    /**
     * Convert to string
     * @return string
     */
    public function __toString();
}
