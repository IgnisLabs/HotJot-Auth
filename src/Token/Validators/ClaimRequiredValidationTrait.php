<?php

namespace IgnisLabs\HotJot\Auth\Token\Validators;

use IgnisLabs\HotJot\Auth\Contracts\Token;
use IgnisLabs\HotJot\Auth\Exceptions\Validation\ClaimRequiredException;

trait ClaimRequiredValidationTrait {

    /**
     * @param string $claim
     * @param Token  $token
     */
    private function validateRequiredClaim(string $claim, Token $token) : void {
        try {
            if (!$token->getClaim($claim)) {
                throw new ClaimRequiredException($claim);
            }
        } catch (\OutOfBoundsException $exception) {
            throw new ClaimRequiredException($claim);
        }
    }
}
