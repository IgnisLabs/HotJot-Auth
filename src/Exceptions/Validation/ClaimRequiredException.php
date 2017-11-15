<?php

namespace IgnisLabs\HotJot\Auth\Exceptions\Validation;

use Throwable;

class ClaimRequiredException extends ValidationException {

    public function __construct($claim, $code = 0, Throwable $previous = null) {
        parent::__construct($claim, "Required claim [$claim] missing", $code, $previous);
    }
}
