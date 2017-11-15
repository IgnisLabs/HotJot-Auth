<?php

namespace IgnisLabs\HotJot\Auth\Contracts\Token;

interface IdGenerator {

    /**
     * Generate a jti
     * @return string
     */
    public function generate() : string;
}
