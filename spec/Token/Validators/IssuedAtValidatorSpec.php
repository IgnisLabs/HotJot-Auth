<?php

namespace spec\IgnisLabs\HotJot\Token\Validators;

use Carbon\Carbon;
use IgnisLabs\HotJot\Contracts\Token;
use IgnisLabs\HotJot\Exceptions\Validation\InvalidIssuedDateException;
use IgnisLabs\HotJot\Token\Validators\IssuedAtValidator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IssuedAtValidatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(IssuedAtValidator::class);
    }

    function it_passes_validation_if_token_was_issued_in_the_past(Token $token)
    {
        $exp = Carbon::parse('yesterday');
        $token->issuedAt()->willReturn($exp);
        $this->shouldNotThrow(\Exception::class)->duringValidate($token);
    }

    function it_throws_exception_if_token_is_issued_in_the_future(Token $token)
    {
        $exp = Carbon::parse('tomorrow');
        $token->issuedAt()->willReturn($exp);
        $this->shouldThrow(InvalidIssuedDateException::class)->duringValidate($token);
    }
}
