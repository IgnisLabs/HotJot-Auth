<?php

namespace IgnisLabs\HotJot\Auth\Exceptions\Validation;

use Throwable;

class TokenUsedTooSoonException extends ValidationException {
    public function __construct($message = "The token is not supposed to be used yet", $code = 0, Throwable $previous = null) {
        parent::__construct('nbf', $message, $code, $previous);
    }
}
