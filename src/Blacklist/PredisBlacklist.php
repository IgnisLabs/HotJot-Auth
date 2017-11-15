<?php

namespace IgnisLabs\HotJot\Auth\Blacklist;

use IgnisLabs\HotJot\Auth\Contracts\Blacklist;
use IgnisLabs\HotJot\Token;
use Predis\ClientInterface;

class PredisBlacklist implements Blacklist {

    /**
     * @var ClientInterface
     */
    private $predis;

    /**
     * @var string
     */
    private $keyPrefix;

    /**
     * PredisBlacklist constructor.
     * @param ClientInterface $predis
     * @param string          $keyPrefix
     */
    public function __construct(ClientInterface $predis, $keyPrefix = 'hotjot:blacklist') {
        $this->predis = $predis;
        $this->keyPrefix = $keyPrefix;
    }

    /**
     * Add JWT to blacklist until it's expiration or indefinitely if it has no expiration date
     * @param Token $token
     */
    public function add(Token $token) {
        $key = $this->key($token->getClaim('jti'));
        $this->predis->set($key, $token->getPayload());
        $this->predis->expireat($key, $token->getClaim('exp')->getTimestamp());
    }

    /**
     * Check if token is blacklisted
     * @param string $jti
     * @return bool
     */
    public function has(string $jti) : bool {
        return (bool) $this->predis->get($this->key($jti));
    }

    /**
     * Prefix a jti to form a redis key
     * @param string $jti
     * @return string
     */
    private function key(string $jti) : string {
        return sprintf("%s:%s", $this->keyPrefix, $jti);
    }
}
