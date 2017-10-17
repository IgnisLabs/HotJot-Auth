<?php

namespace IgnisLabs\HotJot\Exceptions\Validation;

use Throwable;

class TokenExpiredException extends ValidationException {
    public function __construct($message = "The token is expired", $code = 0, Throwable $previous = null) {
        parent::__construct('exp', $message, $code, $previous);
    }
}
