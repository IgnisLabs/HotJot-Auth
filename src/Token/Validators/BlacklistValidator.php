<?php

namespace IgnisLabs\HotJot\Auth\Token\Validators;

use IgnisLabs\HotJot\Token;
use IgnisLabs\HotJot\Contracts\TokenValidator;
use IgnisLabs\HotJot\Auth\Contracts\Blacklist;
use IgnisLabs\HotJot\Auth\Exceptions\Validation\TokenBlacklistedException;

class BlacklistValidator implements TokenValidator {

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
        if ($this->blacklist->has($token->getClaim('jti'))) {
            throw new TokenBlacklistedException;
        }
    }
}
