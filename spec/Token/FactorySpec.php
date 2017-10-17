<?php

namespace spec\IgnisLabs\HotJot\Token;

use IgnisLabs\HotJot\Contracts\Token;
use IgnisLabs\HotJot\Token\Factory;
use Lcobucci\JWT\Signer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FactorySpec extends ObjectBehavior
{
    function let(Signer $signer)
    {
        $key = 'signing key';
        $this->beConstructedWith($signer, $key);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Factory::class);
    }

    function it_should_create_a_new_token()
    {
        $claims = ['jti' => 'token id'];
        $headers = ['foo' => 'bar'];
        /** @var Token $token */
        $token = $this->create($claims, $headers);
        $token->shouldBeAnInstanceOf(Token::class);
        $token->id()->shouldBe('token id');
        $token->getHeader('foo')->shouldBe('bar');
    }
}
