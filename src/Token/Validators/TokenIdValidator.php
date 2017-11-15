<?php

namespace IgnisLabs\HotJot\Auth\Token\Validators;

use IgnisLabs\HotJot\Auth\Contracts\Token;
use IgnisLabs\HotJot\Auth\Contracts\Token\Validator;

class TokenIdValidator implements Validator {

    use ClaimRequiredValidationTrait;

    /**
     * Validate a token
     * @param Token $token
     */
    public function validate(Token $token) : void {
        $this->validateRequiredClaim('jti', $token);
    }
}
