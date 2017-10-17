<?php

namespace IgnisLabs\HotJot\Token\Validators;

use Carbon\Carbon;
use IgnisLabs\HotJot\Contracts\Token;
use IgnisLabs\HotJot\Contracts\Token\Validator;
use IgnisLabs\HotJot\Exceptions\Validation\TokenUsedTooSoonException;

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
