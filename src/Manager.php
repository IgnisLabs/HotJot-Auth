<?php

namespace IgnisLabs\HotJot;

use Carbon\Carbon;
use IgnisLabs\HotJot\Contracts\RequestParser;
use IgnisLabs\HotJot\Contracts\Blacklist;
use IgnisLabs\HotJot\Contracts\Manager as ManagerContract;
use IgnisLabs\HotJot\Contracts\Token\Factory;
use IgnisLabs\HotJot\Contracts\Token;
use IgnisLabs\HotJot\Exceptions\TokenCannotBeRefreshedException;

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
     * Manager constructor.
     * @param Factory               $factory
     * @param RequestParser         $parser
     * @param Blacklist             $blacklist
     * @param int                   $ttr Time to Refresh in days
     * @param Validator $validator
     */
    public function __construct(Factory $factory, RequestParser $parser, Blacklist $blacklist, $ttr = 15, Validator $validator) {
        $this->factory = $factory;
        $this->parser = $parser;
        $this->blacklist = $blacklist;
        $this->ttr = $ttr;
        $this->validator = $validator;
    }

    /**
     * Create a new token
     * @param array $claims
     * @param array $headers
     * @return Token
     */
    public function create(array $claims, array $headers = []) : Token {
        return $this->factory->create($claims, $headers);
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
     * @param Token $token
     * @return Token
     */
    public function refresh(Token $token) : Token {
        $this->validate($token, 'exp');

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
        $this->validator->validate($token, ...$excludeValidators);
    }
}
