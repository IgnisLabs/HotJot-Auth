<?php

namespace IgnisLabs\HotJot\Auth\Contracts\Token;

use IgnisLabs\HotJot\Token;

interface Factory {

    /**
     * Create token
     * @param array    $claims
     * @param array    $headers
     * @param int|null $ttl TTL in minutes
     * @return Token
     */
    public function create(array $claims, array $headers = [], int $ttl = null) : Token;
}
