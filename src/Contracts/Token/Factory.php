<?php

namespace IgnisLabs\HotJot\Contracts\Token;

interface Factory {

    /**
     * Create token
     * @param array $claims
     * @param array $headers
     * @return Token
     */
    public function create(array $claims, array $headers = []) : Token;
}
