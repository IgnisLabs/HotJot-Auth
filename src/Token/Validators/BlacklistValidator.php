<?php

namespace IgnisLabs\HotJot\Auth\Token\Validators;

use IgnisLabs\HotJot\Auth\Contracts\Blacklist;
use IgnisLabs\HotJot\Auth\Contracts\Token;
use IgnisLabs\HotJot\Auth\Contracts\Token\Validator;
use IgnisLabs\HotJot\Auth\Exceptions\Validation\TokenBlacklistedException;

class BlacklistValidator implements Validator {

    /**
     * @var Blacklist
     */
    private $blacklist;

    /**
     * BlacklistValidator constructor.
     * @param Blacklist $blacklist
     */
    public function __construct(Blacklist $blacklist) {
        $this->blacklist = $blacklist;
    }

    /**
     * Validate a token
     * @param Token $token
     */
    public function validate(Token $token) : void {
        try {
            if ($this->blacklist->has($token->id())) {
                throw new TokenBlacklistedException;
            }
        } catch (\OutOfBoundsException $exception) {}
    }
}
