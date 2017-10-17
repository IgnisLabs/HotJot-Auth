<?php

namespace IgnisLabs\HotJot\Exceptions;

use Throwable;

class AuthorizationHeaderNotFound extends \UnexpectedValueException {
    public function __construct($message = "Request has no authorization header", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
