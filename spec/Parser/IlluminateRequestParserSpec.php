<?php

namespace spec\IgnisLabs\HotJot\Auth\Parser;

use IgnisLabs\HotJot\Parser;
use IgnisLabs\HotJot\Token;
use IgnisLabs\HotJot\Auth\Exceptions\BearerTokenNotFound;
use IgnisLabs\HotJot\Auth\Parser\IlluminateRequestParser;
use Illuminate\Http\Request;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IlluminateRequestParserSpec extends ObjectBehavior
{
    function let(Request $request, Parser $parser)
    {
        $this->beConstructedWith($request, $parser);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(IlluminateRequestParser::class);
    }

    function it_should_parse_token_from_request(Request $request, Parser $parser, Token $token)
    {
        $request->bearerToken()->willReturn('a.valid.token');
        $parser->parse('a.valid.token')->shouldBeCalled()->willReturn($token);
        $this->parse()->shouldBeAnInstanceOf(Token::class);
    }
}
