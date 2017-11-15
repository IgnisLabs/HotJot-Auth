<?php

namespace spec\IgnisLabs\HotJot\Auth\Token;

use IgnisLabs\HotJot\Token;
use IgnisLabs\HotJot\Contracts\Signer;
use IgnisLabs\HotJot\Auth\Exceptions\SignatureVerificationFailedException;
use IgnisLabs\HotJot\Auth\Token\Verifier;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class VerifierSpec extends ObjectBehavior
{
    function let(Signer $signer)
    {
        $this->beConstructedWith($signer);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Verifier::class);
    }

    function it_should_verify_a_token(Signer $signer, Token $token)
    {
        $signer->verify($token)->shouldBeCalled()->willReturn(true);
        $this->verify($token)->shouldBe(true);
    }
}
