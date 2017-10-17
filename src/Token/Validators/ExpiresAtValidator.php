<?php

namespace IgnisLabs\HotJot\Token\Validators;

use Carbon\Carbon;
use IgnisLabs\HotJot\Contracts\Token;
use IgnisLabs\HotJot\Contracts\Token\Validator;
use IgnisLabs\HotJot\Exceptions\Validation\TokenExpiredException;

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
