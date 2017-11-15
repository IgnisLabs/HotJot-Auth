<?php

namespace spec\IgnisLabs\HotJot\Auth\Token\Validators;

use IgnisLabs\HotJot\Auth\Contracts\Blacklist;
use IgnisLabs\HotJot\Auth\Contracts\Token;
use IgnisLabs\HotJot\Auth\Exceptions\Validation\TokenBlacklistedException;
use IgnisLabs\HotJot\Auth\Token\Validators\BlacklistValidator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BlacklistValidatorSpec extends ObjectBehavior
{
    function let(Blacklist $blacklist)
    {
        $this->beConstructedWith($blacklist);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(BlacklistValidator::class);
    }

    function it_should_pass_validaton_if_token_is_not_blacklisted(Blacklist $blacklist, Token $token)
    {
        $blacklist->has('token id')->willReturn(false);
        $token->id()->willReturn('token id');

        $this->validate($token);
    }

    function it_should_fail_validation_if_token_is_blacklisted(Blacklist $blacklist, Token $token)
    {
        $blacklist->has('token id')->willReturn(true);
        $token->id()->willReturn('token id');

        $this->shouldThrow(TokenBlacklistedException::class)->duringValidate($token);
    }
}
