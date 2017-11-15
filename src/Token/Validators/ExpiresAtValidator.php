<?php

namespace IgnisLabs\HotJot\Auth\Token\Validators;

use Carbon\Carbon;
use IgnisLabs\HotJot\Auth\Contracts\Token;
use IgnisLabs\HotJot\Auth\Contracts\Token\Validator;
use IgnisLabs\HotJot\Auth\Exceptions\Validation\TokenExpiredException;

class ExpiresAtValidator implements Validator {

    use ClaimRequiredValidationTrait;

    /**
     * Validate a token
     * @param Token $token
     */
    public function validate(Token $token) : void {
        $this->validateRequiredClaim('exp', $token);
        if (Carbon::now() >= $token->expiresAt()) {
            throw new TokenExpiredException;
        }
    }
}
