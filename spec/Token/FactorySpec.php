<?php

namespace spec\IgnisLabs\HotJot\Token;

use Carbon\Carbon;
use IgnisLabs\HotJot\Contracts\Token;
use IgnisLabs\HotJot\Contracts\Token\IdGenerator;
use IgnisLabs\HotJot\Token\Factory;
use Lcobucci\JWT\Signer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FactorySpec extends ObjectBehavior
{
    function let(IdGenerator $idGenerator, Signer $signer)
    {
        $idGenerator->generate()->willReturn('token id');
        $key = 'private key';
        $this->beConstructedWith($idGenerator, $signer, $key);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Factory::class);
    }

    function it_should_create_a_new_token()
    {
        $claims = ['foo' => 'bar'];
        $headers = ['baz' => 'qux'];
        /** @var Token $token */
        $token = $this->create($claims, $headers);
        $token->shouldBeAnInstanceOf(Token::class);
        $token->id()->shouldBe('token id');
        $token->getClaim('foo')->shouldBe('bar');
        $token->getHeader('baz')->shouldBe('qux');
        $token->expiresAt()->diffInMinutes(Carbon::now())->shouldBe(10);
    }
}
