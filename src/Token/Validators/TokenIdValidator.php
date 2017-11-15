<?php

namespace IgnisLabs\HotJot\Auth\Token\Validators;

use IgnisLabs\HotJot\Token;
use IgnisLabs\HotJot\Contracts\TokenValidator;
use IgnisLabs\HotJot\Validators\ClaimRequiredTrait;

class TokenIdValidator implements TokenValidator {

    use ClaimRequiredTrait;

    public function __construct() {
        $this->isRequired = true;
    }

    /**
     * Validate a token
     * @param Token $token
     */
    public function validate(Token $token) : void {
        $this->validateRequiredClaim($token, 'jti');
    }
}
