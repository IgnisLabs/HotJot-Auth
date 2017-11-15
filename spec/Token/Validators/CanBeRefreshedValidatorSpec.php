<?php

namespace spec\IgnisLabs\HotJot\Auth\Token\Validators;

use IgnisLabs\HotJot\Auth\Exceptions\Validation\TokenCannotBeRefreshedException;
use IgnisLabs\HotJot\Auth\Token\Validators\CanBeRefreshedValidator;
use IgnisLabs\HotJot\Token;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CanBeRefreshedValidatorSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(15);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CanBeRefreshedValidator::class);
    }

    function it_should_pass_if_token_was_issued_less_that_ttr_days_ago(Token $token)
    {
        $token->getClaim('iat')->willReturn(new \DateTime('-15 days'));

        $this->validate($token);
    }

    function it_should_fail_if_token_was_issued_more_that_ttr_days_ago(Token $token)
    {
        $token->getClaim('iat')->willReturn(new \DateTime('-16 days'));

        $this->shouldThrow(TokenCannotBeRefreshedException::class)->duringValidate($token);
    }
}
