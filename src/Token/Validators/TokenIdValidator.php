<?php

namespace IgnisLabs\HotJot\Token\Validators;

use IgnisLabs\HotJot\Contracts\Token;
use IgnisLabs\HotJot\Contracts\Token\Validator;

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
