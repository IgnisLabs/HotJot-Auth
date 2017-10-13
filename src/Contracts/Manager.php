<?php

namespace IgnisLabs\HotJot\Contracts;

use IgnisLabs\HotJot\Contracts\Token\Token;

interface Manager {

    /**
     * Create a new token
     * @param array $claims
     * @param array $headers
     * @return Token
     */
    public function create(array $claims, array $headers = []) : Token;

    /**
     * Parse token from current request
     * @return Token
     */
    public function parse() : Token;

    /**
     * Get a new token based on a previous valid one
     * @param Token $token
     * @return Token
     */
    public function refresh(Token $token) : Token;

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
     * @param array ...$excludeClaims
     */
    public function validate(Token $token, ...$excludeClaims);
}
