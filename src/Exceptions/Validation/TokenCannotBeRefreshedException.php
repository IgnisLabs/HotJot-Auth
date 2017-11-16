<?php

namespace IgnisLabs\HotJot\Auth\Exceptions\Validation;

use IgnisLabs\HotJot\Exception\Validation\ValidationException;
use Throwable;

class TokenCannotBeRefreshedException extends ValidationException {

    public function __construct($message = "Token cannot be refreshed", $code = 0, Throwable $previous = null) {
        parent::__construct('iat', $message, $code, $previous);
    }
}
