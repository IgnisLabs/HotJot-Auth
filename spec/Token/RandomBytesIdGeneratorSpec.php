<?php

namespace spec\IgnisLabs\HotJot\Token;

use IgnisLabs\HotJot\Token\RandomBytesIdGenerator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RandomBytesIdGeneratorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(RandomBytesIdGenerator::class);
    }

    function it_should_generate_a_random_id()
    {
        $this->generate()->shouldMatch('/^[a-f0-9]{32}$/i');
    }

    function it_should_generate_a_random_id_with_any_length_in_bytes()
    {
        $this->beConstructedWith(8);
        $this->generate()->shouldMatch('/^[a-f0-9]{16}$/i');
    }
}
