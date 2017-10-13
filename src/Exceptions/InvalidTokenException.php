<?php

namespace IgnisLabs\HotJot\Exceptions;

use Throwable;

class InvalidTokenException extends \UnexpectedValueException {

    public function __construct($message = "Token is not valid", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
