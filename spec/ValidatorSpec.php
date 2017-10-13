<?php

namespace spec\IgnisLabs\HotJot;

use IgnisLabs\HotJot\Contracts\Token\Validator as TokenValidator;
use IgnisLabs\HotJot\Validator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ValidatorSpec extends ObjectBehavior
{
    function let(TokenValidator $tokenValidator)
    {
        $this->beConstructedWith($tokenValidator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Validator::class);
    }
}
