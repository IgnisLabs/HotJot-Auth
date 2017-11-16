<?php

namespace IgnisLabs\HotJot\Auth\Exceptions\Validation;

use IgnisLabs\HotJot\Exception\Validation\ValidationException;
use Throwable;

class TokenBlacklistedException extends ValidationException {
    public function __construct($message = "", $code = 0, Throwable $previous = null) {
        parent::__construct('jti', "Token is blacklisted", $code, $previous);
    }
}
