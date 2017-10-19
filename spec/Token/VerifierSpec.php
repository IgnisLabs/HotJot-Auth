<?php

namespace spec\IgnisLabs\HotJot\Token;

use IgnisLabs\HotJot\Contracts\Token;
use IgnisLabs\HotJot\Exceptions\SignatureVerificationFailedException;
use IgnisLabs\HotJot\Token\Verifier;
use Lcobucci\JWT\Signer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class VerifierSpec extends ObjectBehavior
{
    function let(Signer $signer)
    {
        $this->beConstructedWith($signer, 'secret key');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Verifier::class);
    }

    function it_should_verify_token_signature(Signer $signer, Token $token)
    {
        $token->signature()->willReturn('foo');
        $token->__toString()->willReturn('a.token.here');
        $signer->verify('foo', 'a.token.here', 'secret key')->willReturn(true);

        $this->verify($token);
    }

    function it_should_verify_with_public_key_if_provided(Signer $signer, Token $token)
    {
        $token->signature()->willReturn('foo');
        $token->__toString()->willReturn('a.token.here');
        $signer->verify('foo', 'a.token.here', 'public key')->willReturn(true);

        $this->beConstructedWith($signer, 'public key');

        $this->verify($token);
    }

    function it_should_fail_token_verification_if_signature(Signer $signer, Token $token)
    {
        $token->signature()->willReturn('foo');
        $token->__toString()->willReturn('a.token.here');
        $signer->verify('foo', 'a.token.here', 'secret key')->willReturn(false);

        $this->shouldThrow(SignatureVerificationFailedException::class)->duringVerify($token);
    }
}
