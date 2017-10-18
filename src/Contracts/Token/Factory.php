<?php

namespace IgnisLabs\HotJot\Contracts\Token;

use IgnisLabs\HotJot\Contracts\Token;

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
