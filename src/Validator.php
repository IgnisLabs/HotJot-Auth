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
     * @param TokenValidator[] $validators
     * @return Validator
     */
    public function addValidators(TokenValidator ...$validators) : Validator {
        return new static(...$this->validators, ...$validators);
    }

    /**
     * @param TokenValidator[] ...$validators
     * @return Validator
     */
    public function replaceValidators(TokenValidator ...$validators) : Validator {
        return new static(...$validators);
    }

    /**
     * Validate token
     * Validators should throw exceptions with descriptive messages
     * @param Token $token
     * @param array ...$excludeValidators
     */
    public function validate(Token $token, ...$excludeValidators) : void {
        $validators = array_filter($this->validators, function(TokenValidator $validator) use ($excludeValidators) {
            return !in_array(get_class($validator), $excludeValidators);
        });
        foreach ($validators as $validator) {
            $validator->validate($token);
        }
    }
}
