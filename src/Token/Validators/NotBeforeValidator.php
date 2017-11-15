<?php

namespace IgnisLabs\HotJot\Auth\Token\Validators;

use Carbon\Carbon;
use IgnisLabs\HotJot\Auth\Contracts\Token;
use IgnisLabs\HotJot\Auth\Contracts\Token\Validator;
use IgnisLabs\HotJot\Auth\Exceptions\Validation\TokenUsedTooSoonException;

class NotBeforeValidator implements Validator {

    /**
     * Validate a token
     * @param Token $token
     */
    public function validate(Token $token) : void {
        try {
            if (Carbon::now() < $token->notBefore()) {
                throw new TokenUsedTooSoonException;
            }
        } catch (\OutOfBoundsException $exception) {}
    }
}
