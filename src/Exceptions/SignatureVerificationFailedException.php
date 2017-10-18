<?php

namespace IgnisLabs\HotJot\Exceptions;

use Throwable;

class SignatureVerificationFailedException extends \UnexpectedValueException {
    public function __construct($message = "Token signature verification failed", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
