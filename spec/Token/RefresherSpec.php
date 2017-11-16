<?php

namespace spec\IgnisLabs\HotJot\Auth\Token;

use IgnisLabs\HotJot\Auth\Contracts\Blacklist;
use IgnisLabs\HotJot\Auth\Contracts\Token\Verifier;
use IgnisLabs\HotJot\Auth\Exceptions\Validation\TokenCannotBeRefreshedException;
use IgnisLabs\HotJot\Auth\Token\Factory;
use IgnisLabs\HotJot\Auth\Token\Refresher;
use IgnisLabs\HotJot\Exception\SignatureVerificationException;
use IgnisLabs\HotJot\Token;
use IgnisLabs\HotJot\Validator;
use IgnisLabs\HotJot\Validators\ExpiresAtValidator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RefresherSpec extends ObjectBehavior
{
    function let(Verifier $verifier, Validator $validator, Blacklist $blacklist, Factory $factory)
    {
        $validator->addValidators(Argument::any())->willReturn($validator);
        $this->beConstructedWith($verifier, $validator, $blacklist, $factory, 15);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Refresher::class);
    }

    function it_should_refresh_a_token(Verifier $verifier, Validator $validator, Blacklist $blacklist, Factory $factory, Token $token, Token $newToken)
    {
        $verifier->verify($token)->shouldBeCalled()->willReturn(true);
        $validator->validate($token, ExpiresAtValidator::class)->shouldBeCalled();
        $blacklist->add($token)->shouldBeCalled();

        $claims = ['foo' => 'bar'];
        $headers = ['baz' => 'qux'];
        $token->getClaims()->willReturn($claims);
        $token->getHeaders()->willReturn($headers);
        $factory->create($claims, $headers)->shouldBeCalled()->willReturn($newToken);

        $this->refresh($token)->shouldBe($newToken);
    }

    function it_should_blacklist_immediately_and_rethrow_exception_if_verification_fails(Verifier $verifier, Blacklist $blacklist, Token $token)
    {
        $verifier->verify($token)->shouldBeCalled()->willReturn(false);
        $blacklist->add($token)->shouldBeCalled();

        $this->shouldThrow(SignatureVerificationException::class)->duringRefresh($token);
    }

    function it_should_blacklist_immediately_and_rethrow_exception_if_validation_fails(Verifier $verifier, Validator $validator, Blacklist $blacklist, Token $token)
    {
        $verifier->verify($token)->shouldBeCalled()->willReturn(true);
        $validator->validate($token, ExpiresAtValidator::class)->willThrow(TokenCannotBeRefreshedException::class);
        $blacklist->add($token)->shouldBeCalled();

        $this->shouldThrow(TokenCannotBeRefreshedException::class)->duringRefresh($token);
    }
}
