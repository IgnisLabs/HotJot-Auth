<?php

namespace IgnisLabs\HotJot\Contracts\Token;

interface IdGenerator {

    /**
     * Generate a jti
     * @return string
     */
    public function generate() : string;
}
