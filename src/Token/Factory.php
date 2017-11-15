<?php

namespace IgnisLabs\HotJot\Auth\Token;

use Carbon\Carbon;
use IgnisLabs\HotJot\Auth\Contracts\Token\IdGenerator;
use IgnisLabs\HotJot\Token;
use IgnisLabs\HotJot\Auth\Contracts\Token\Factory as FactoryContract;
use IgnisLabs\HotJot\Factory as HotJotFactory;

class Factory implements FactoryContract {

    /**
     * @var HotJotFactory
     */
    private $factory;

    /**
     * @var IdGenerator
     */
    private $idGenerator;

    /**
     * @var array
     */
    private $defaultClaims;

    /**
     * @var int
     */
    private $ttl;

    /**
     * Factory constructor.
     * @param HotJotFactory $factory
     * @param IdGenerator   $idGenerator
     * @param array         $defaultClaims
     * @param int           $ttl
     */
    public function __construct(HotJotFactory $factory, IdGenerator $idGenerator, array $defaultClaims = [], int $ttl = 10) {
        $this->factory = $factory;
        $this->idGenerator = $idGenerator;
        $this->defaultClaims = $defaultClaims;
        $this->ttl = $ttl;
    }

    /**
     * Create token
     * @param array    $claims
     * @param array    $headers
     * @param int|null $ttl TTL in minutes
     * @return Token
     */
    public function create(array $claims, array $headers = [], int $ttl = null) : Token {
        // Set/Override default claims
        $claims = array_merge($this->defaultClaims, $claims);

        // Set mandatory claims
        $ttl = $ttl ?? $this->ttl;
        $claims['jti'] = $this->idGenerator->generate();
        $claims['exp'] = (new \DateTime("+$ttl minutes"))->getTimestamp();

        return $this->factory->create($claims, $headers);
    }
}
