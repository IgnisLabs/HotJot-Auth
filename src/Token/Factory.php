<?php

namespace IgnisLabs\HotJot\Auth\Token;

use Carbon\Carbon;
use IgnisLabs\HotJot\Auth\Contracts\Token\IdGenerator;
use IgnisLabs\HotJot\Auth\Token;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer;
use IgnisLabs\HotJot\Auth\Contracts\Token\Factory as FactoryContract;
use IgnisLabs\HotJot\Auth\Contracts\Token as TokenContract;

class Factory implements FactoryContract {

    /**
     * @var Signer
     */
    private $signer;

    /**
     * @var string
     */
    private $key;

    /**
     * @var IdGenerator
     */
    private $idGenerator;

    /**
     * @var int
     */
    private $ttl;

    /**
     * Factory constructor.
     * @param IdGenerator $idGenerator
     * @param Signer      $signer
     * @param string      $privateKey
     * @param int         $ttl
     */
    public function __construct(IdGenerator $idGenerator, Signer $signer, string $privateKey, int $ttl = 10) {
        $this->idGenerator = $idGenerator;
        $this->signer = $signer;
        $this->key = $privateKey;
        $this->ttl = $ttl;
    }

    /**
     * Create token
     * @param array    $claims
     * @param array    $headers
     * @param int|null $ttl
     * @return TokenContract
     */
    public function create(array $claims, array $headers = [], int $ttl = null) : TokenContract {
        $builder = new Builder();

        foreach ($claims as $name => $value) {
            $builder->set($name, $value);
        }

        foreach ($headers as $name => $value) {
            $builder->setHeader($name, $value);
        }

        $ttl = $ttl ?? $this->ttl;

        $builder
            ->setId($this->idGenerator->generate())
            ->setExpiration(Carbon::parse("$ttl minutes")->getTimestamp());

        return new Token($builder->sign($this->signer, $this->key)->getToken());
    }
}
