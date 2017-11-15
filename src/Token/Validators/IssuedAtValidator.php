<?php

namespace IgnisLabs\HotJot\Auth\Token\Validators;

use Carbon\Carbon;
use IgnisLabs\HotJot\Auth\Contracts\Token;
use IgnisLabs\HotJot\Auth\Contracts\Token\Validator;
use IgnisLabs\HotJot\Auth\Exceptions\Validation\InvalidIssuedDateException;

class IssuedAtValidator implements Validator {

    /**
     * Validate a token
     * @param Token $token
     */
    public function validate(Token $token) : void {
        try {
            if ($token->issuedAt() > Carbon::now()) {
                throw new InvalidIssuedDateException;
            }
        } catch (\OutOfBoundsException $exception) {}
    }
}
