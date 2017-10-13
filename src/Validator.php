<?php

namespace IgnisLabs\HotJot;

use IgnisLabs\HotJot\Contracts\Token\Token;
use IgnisLabs\HotJot\Contracts\Token\Validator as TokenValidator;

class Validator {

    /**
     * @var TokenValidator[]
     */
    private $validators;

    /**
     * Validator constructor.
     * @param TokenValidator[] ...$validators
     */
    public function __construct(TokenValidator ...$validators) {
        $this->validators = $validators;
    }

    /**
     * @param TokenValidator $validator
     * @return Validator
     */
    public function addValidator(TokenValidator $validator) : Validator {
        // @todo implement this method!
    }

    /**
     * @param TokenValidator[] ...$validators
     * @return Validator
     */
    public function replaceValidators(TokenValidator ...$validators) : Validator {
        // @todo implement this method!
    }

    /**
     * Validate token
     * Validators should throw exceptions with descriptive message
     * @param Token $token
     * @param array ...$excludeValidators
     */
    public function validate(Token $token, ...$excludeValidators) : void {
        // @todo implement this method!
    }
}
