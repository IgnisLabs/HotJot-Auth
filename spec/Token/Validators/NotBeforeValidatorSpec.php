<?php

namespace spec\IgnisLabs\HotJot\Auth\Token\Validators;

use Carbon\Carbon;
use IgnisLabs\HotJot\Auth\Contracts\Token;
use IgnisLabs\HotJot\Auth\Exceptions\Validation\TokenUsedTooSoonException;
use IgnisLabs\HotJot\Auth\Token\Validators\NotBeforeValidator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NotBeforeValidatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(NotBeforeValidator::class);
    }

    function it_passes_validation_if_token_is_used_after_not_before_date(Token $token)
    {
        $exp = Carbon::parse('yesterday');
        $token->notBefore()->willReturn($exp);
        $this->shouldNotThrow(\Exception::class)->duringValidate($token);
    }

    function it_throws_exception_if_token_is_used_before_not_before_date(Token $token)
    {
        $exp = Carbon::parse('tomorrow');
        $token->notBefore()->willReturn($exp);
        $this->shouldThrow(TokenUsedTooSoonException::class)->duringValidate($token);
    }
}
