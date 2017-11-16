<?php

namespace IgnisLabs\HotJot\Auth\Token;

use IgnisLabs\HotJot\Auth\Contracts\Blacklist;
use IgnisLabs\HotJot\Auth\Contracts\Token\Factory;
use IgnisLabs\HotJot\Auth\Contracts\Token\Refresher as RefresherContract;
use IgnisLabs\HotJot\Auth\Contracts\Token\Verifier;
use IgnisLabs\HotJot\Auth\Token\Validators\CanBeRefreshedValidator;
use IgnisLabs\HotJot\Exception\SignatureVerificationException;
use IgnisLabs\HotJot\Exception\UnsignedTokenException;
use IgnisLabs\HotJot\Exception\Validation\ValidationException;
use IgnisLabs\HotJot\Token;
use IgnisLabs\HotJot\Validator;
use IgnisLabs\HotJot\Validators\ExpiresAtValidator;

class Refresher implements RefresherContract {

    /**
     * @var Factory
     */
    private $factory;

    /**
     * @var Blacklist
     */
    private $blacklist;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var Verifier
     */
    private $verifier;

    /**
     * @var int
     */
    private $ttr;

    public function __construct(Verifier $verifier, Validator $validator, Blacklist $blacklist, Factory $factory, int $ttr) {
        $this->verifier = $verifier;
        $this->validator = $validator->addValidators(new CanBeRefreshedValidator($ttr));
        $this->blacklist = $blacklist;
        $this->factory = $factory;
    }

    /**
     * Get a new token based on a previous valid one
     * It validates and verifies the token. You can exclude certain validators from running when refreshing
     * @param Token $token
     * @param array $excludeValidators
     * @return Token
     */
    public function refresh(Token $token, ...$excludeValidators) : Token {
        $this->verify($token);
        $this->validate($token, $excludeValidators);
        $this->blacklist->add($token);

        return $this->factory->create($token->getClaims(), $token->getHeaders());
    }

    private function verify($token) {
        try {
            if (!$this->verifier->verify($token)) {
                throw new SignatureVerificationException("Token signature verification failed");
            }
        } catch (SignatureVerificationException|UnsignedTokenException $exception) {
            $this->blacklist->add($token);
            throw $exception;
        }
    }

    private function validate($token, array $excludeValidators) {
        try {
            $this->validator->validate($token, ExpiresAtValidator::class, ...$excludeValidators);
        } catch (ValidationException $exception) {
            $this->blacklist->add($token);
            throw $exception;
        }
    }
}
