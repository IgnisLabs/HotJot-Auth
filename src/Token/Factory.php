<?php

namespace IgnisLabs\HotJot\Token;

use IgnisLabs\HotJot\Token;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer;
use IgnisLabs\HotJot\Contracts\Token\Factory as FactoryContract;
use IgnisLabs\HotJot\Contracts\Token as TokenContract;

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
     * Factory constructor.
     * @param Signer $signer
     * @param string $key
     */
    public function __construct(Signer $signer, string $key) {
        $this->signer = $signer;
        $this->key = $key;
    }

    /**
     * Create token
     * @param array $claims
     * @param array $headers
     * @return TokenContract
     */
    public function create(array $claims, array $headers = []) : TokenContract {
        $builder = new Builder();

        foreach ($claims as $name => $value) {
            $builder->set($name, $value);
        }

        foreach ($headers as $name => $value) {
            $builder->setHeader($name, $value);
        }

        return new Token($builder->sign($this->signer, $this->key)->getToken());
    }
}
