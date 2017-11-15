<?php

namespace spec\IgnisLabs\HotJot\Auth\Token;

use Carbon\Carbon;
use IgnisLabs\HotJot\Factory as HotJotFactory;
use IgnisLabs\HotJot\Token;
use IgnisLabs\HotJot\Auth\Contracts\Token\IdGenerator;
use IgnisLabs\HotJot\Auth\Token\Factory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FactorySpec extends ObjectBehavior
{
    function let(HotJotFactory $factory, IdGenerator $idGenerator)
    {
        $idGenerator->generate()->willReturn('token id');
        $defaultClaims = [
            'default' => 'value',
        ];
        $this->beConstructedWith($factory, $idGenerator, $defaultClaims);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Factory::class);
    }

    function it_should_create_a_new_token(HotJotFactory $factory, Token $token)
    {
        $claims = ['foo' => 'bar'];
        $headers = ['baz' => 'qux'];

        $expectedClaims = [
            'default' => 'value',
            'foo' => 'bar',
            'jti' => 'token id',
            'iat' => (new \DateTime())->getTimestamp(),
            'exp' => (new \DateTime('+10 minutes'))->getTimestamp(),
        ];

        $factory->create($expectedClaims, $headers)->shouldBeCalled()->willReturn($token);

        $this->create($claims, $headers)->shouldBe($token);
    }
}
