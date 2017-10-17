<?php

namespace IgnisLabs\HotJot\Token;

use IgnisLabs\HotJot\Contracts\Token\IdGenerator;

class RandomBytesIdGenerator implements IdGenerator {

    /**
     * @var int
     */
    private $bytes;

    /**
     * RandomBytesIdGenerator constructor.
     * @param int $bytes
     */
    public function __construct($bytes = 16) {
        $this->bytes = $bytes;
    }

    /**
     * Generate a jti
     * @return string
     */
    public function generate() : string {
        return bin2hex(random_bytes($this->bytes));
    }
}
