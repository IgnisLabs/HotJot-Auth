<?php

namespace IgnisLabs\HotJot\Auth;

use Carbon\Carbon;
use IgnisLabs\HotJot\Auth\Contracts\RequestParser;
use IgnisLabs\HotJot\Auth\Contracts\Blacklist;
use IgnisLabs\HotJot\Auth\Contracts\Manager as ManagerContract;
use IgnisLabs\HotJot\Auth\Contracts\Token\Factory;
use IgnisLabs\HotJot\Auth\Contracts\Token;
use IgnisLabs\HotJot\Auth\Contracts\Token\Verifier;
use IgnisLabs\HotJot\Auth\Exceptions\SignatureVerificationFailedException;
use IgnisLabs\HotJot\Auth\Exceptions\TokenCannotBeRefreshedException;
use IgnisLabs\HotJot\Auth\Exceptions\Validation\ValidationException;
use IgnisLabs\HotJot\Auth\Token\Validators\ExpiresAtValidator;

class Manager implements ManagerContract {

    /**
     * @var Factory
     */
    private $factory;

    /**
     * @var RequestParser
     */
    private $parser;

    /**
     * @var Blacklist
     */
    private $blacklist;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var int
     */
    private $ttr;

    /**
     * @var Verifier
     */
    private $verifier;

    /**
     * Manager constructor.
     * @param Factory       $factory
     * @param RequestParser $parser
     * @param Blacklist     $blacklist
     * @param Verifier      $verifier
     * @param Validator     $validator
     * @param int           $ttr TTR in days
     */
    public function __construct(Factory $factory, RequestParser $parser, Blacklist $blacklist, Verifier $verifier, Validator $validator, $ttr = 15) {
        $this->factory = $factory;
        $this->parser = $parser;
        $this->blacklist = $blacklist;
        $this->verifier = $verifier;
        $this->validator = $validator;
        $this->ttr = $ttr;
    }

    /**
     * Create a new token
     * @param array    $claims
     * @param array    $headers
     * @param int|null $ttl TTL in minutes
     * @return Token
     */
    public function create(array $claims, array $headers = [], int $ttl = null) : Token {
        return $this->factory->create($claims, $headers, $ttl);
    }

    /**
     * Parse token from current request
     * @return Token
     */
    public function parse() : Token {
        return $this->parser->parse();
    }

    /**
     * Get a new token based on a previous valid one
     * This validates and verifies the token. You can exclude certain validators from running when refreshing.
     * @param Token $token
     * @param array $excludeValidators
     * @return Token
     */
    public function refresh(Token $token, ...$excludeValidators) : Token {
        $this->validate($token, ExpiresAtValidator::class, ...$excludeValidators);
        $this->verify($token);
        $this->blacklist($token);

        if (Carbon::instance($token->issuedAt())->diffInDays(Carbon::now()) > $this->ttr) {
            throw new TokenCannotBeRefreshedException;
        }

        return $this->factory->create($token->getClaims(), $token->getHeaders());
    }

    /**
     * Check if token is blacklisted
     * @param Token $token
     * @return bool
     */
    public function isBlacklisted(Token $token) : bool {
        return $this->blacklist->has($token->id());
    }

    /**
     * Add token to blacklist
     * @param Token $token
     * @return void
     */
    public function blacklist(Token $token) {
        $this->blacklist->add($token);
    }

    /**
     * Validate token claims
     * @param Token $token
     * @param array $excludeValidators Exclude validators by class name
     */
    public function validate(Token $token, ...$excludeValidators) {
        try {
            $this->validator->validate($token, ...$excludeValidators);
        } catch (ValidationException $exception) {
            $this->blacklist($token);
            throw $exception;
        }
    }

    /**
     * Verify token signature
     * @param Token       $token
     * @throws SignatureVerificationFailedException
     */
    public function verify(Token $token) {
        try {
            $this->verifier->verify($token);
        } catch (SignatureVerificationFailedException $exception) {
            $this->blacklist($token);
            throw $exception;
        }
    }
}
