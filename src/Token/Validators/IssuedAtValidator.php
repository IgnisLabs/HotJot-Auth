<?php

namespace IgnisLabs\HotJot\Token\Validators;

use Carbon\Carbon;
use IgnisLabs\HotJot\Contracts\Token;
use IgnisLabs\HotJot\Contracts\Token\Validator;
use IgnisLabs\HotJot\Exceptions\Validation\InvalidIssuedDateException;

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
