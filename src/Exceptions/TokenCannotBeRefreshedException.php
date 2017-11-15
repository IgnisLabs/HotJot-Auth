<?php

namespace IgnisLabs\HotJot\Auth\Exceptions;

use Throwable;

class TokenCannotBeRefreshedException extends \UnexpectedValueException {

    public function __construct($message = "Token cannot be refreshed", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
