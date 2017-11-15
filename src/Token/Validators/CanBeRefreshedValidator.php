<?php

namespace IgnisLabs\HotJot\Auth\Token\Validators;

use IgnisLabs\HotJot\Token;
use IgnisLabs\HotJot\Contracts\TokenValidator;
use IgnisLabs\HotJot\Auth\Exceptions\Validation\TokenCannotBeRefreshedException;

class CanBeRefreshedValidator implements TokenValidator {

    /**
     * @var int
     */
    private $ttr;

    public function __construct(int $ttr) {
        $this->ttr = $ttr;
    }

    /**
     * Validate a token
     * @param Token $token
     */
    public function validate(Token $token) : void {
        $interval = $token->getClaim('iat')->diff(new \DateTime());
        if (!$interval->invert && $interval->days > $this->ttr) {
            throw new TokenCannotBeRefreshedException;
        }
    }
}
