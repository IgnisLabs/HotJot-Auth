<?php

namespace IgnisLabs\HotJot\Exceptions\Validation;

use Throwable;

class TokenBlacklistedException extends ValidationException {
    public function __construct($message = "", $code = 0, Throwable $previous = null) {
        parent::__construct('jti', "Token is blacklisted", $code, $previous);
    }
}
