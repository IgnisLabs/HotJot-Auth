<?php

namespace spec\IgnisLabs\HotJot\Auth\Token\Validators;

use Carbon\Carbon;
use IgnisLabs\HotJot\Auth\Contracts\Token;
use IgnisLabs\HotJot\Auth\Exceptions\Validation\ClaimRequiredException;
use IgnisLabs\HotJot\Auth\Exceptions\Validation\TokenExpiredException;
use IgnisLabs\HotJot\Auth\Token\Validators\ExpiresAtValidator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExpiresAtValidatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ExpiresAtValidator::class);
    }

    function it_passes_validation_if_token_is_not_expired(Token $token)
    {
        $exp = Carbon::parse('tomorrow');
        $token->getClaim('exp')->willReturn($exp);
        $token->expiresAt()->willReturn($exp);
        $this->shouldNotThrow(\Exception::class)->duringValidate($token);
    }

    function it_requires_expiraton_date(Token $token)
    {
        $token->getClaim('exp')->willReturn();
        $this->shouldThrow(ClaimRequiredException::class)->duringValidate($token);
    }

    function it_throws_exception_if_token_is_expired(Token $token)
    {
        $exp = Carbon::parse('yesterday');
        $token->getClaim('exp')->willReturn($exp);
        $token->expiresAt()->willReturn($exp);
        $this->shouldThrow(TokenExpiredException::class)->duringValidate($token);
    }
}
