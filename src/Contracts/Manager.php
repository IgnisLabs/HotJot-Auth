<?php

namespace IgnisLabs\HotJot\Auth\Contracts;

use IgnisLabs\HotJot\Auth\Contracts\Token;
use IgnisLabs\HotJot\Auth\Exceptions\SignatureVerificationFailedException;

interface Manager {

    /**
     * Create a new token
     * @param array    $claims
     * @param array    $headers
     * @param int|null $ttl TTL in minutes
     * @return Token
     */
    public function create(array $claims, array $headers = [], int $ttl = null) : Token;

    /**
     * Parse token from current request
     * @return Token
     */
    public function parse() : Token;

    /**
     * Get a new token based on a previous valid one
     * This validates and verifies the token. You can exclude certain validators from running when refreshing.
     * @param Token $token
     * @param array $excludeValidators
     * @return Token
     */
    public function refresh(Token $token, ...$excludeValidators) : Token;

    /**
     * Check if token is blacklisted
     * @param Token $token
     * @return bool
     */
    public function isBlacklisted(Token $token) : bool;

    /**
     * Add token to blacklist
     * @param Token $token
     * @return void
     */
    public function blacklist(Token $token);

    /**
     * Validate token claims
     * @param Token $token
     * @param array ...$excludeValidators
     */
    public function validate(Token $token, ...$excludeValidators);

    /**
     * Verify token signature
     * @param Token       $token
     * @throws SignatureVerificationFailedException
     */
    public function verify(Token $token);
}
