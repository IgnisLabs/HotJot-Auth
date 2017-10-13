<?php

namespace IgnisLabs\HotJot\Contracts\Token;

interface Validator {
    public function validate(Token $token);
}
