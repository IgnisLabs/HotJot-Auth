<?php

namespace IgnisLabs\HotJot\Exceptions;

use Throwable;

class BearerTokenNotFound extends \UnexpectedValueException {
    public function __construct($message = "Request has no bearer token", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
