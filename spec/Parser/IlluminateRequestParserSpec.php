<?php

namespace spec\IgnisLabs\HotJot\Parser;

use IgnisLabs\HotJot\Contracts\Token;
use IgnisLabs\HotJot\Exceptions\BearerTokenNotFound;
use IgnisLabs\HotJot\Parser\IlluminateRequestParser;
use Illuminate\Http\Request;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Token as LcobucciToken;
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

    function it_should_parse_token_from_request(Request $request, Parser $parser, LcobucciToken $token)
    {
        $request->bearerToken()->willReturn('a.valid.token');
        $parser->parse('a.valid.token')->shouldBeCalled()->willReturn($token);
        $this->parse()->shouldBeAnInstanceOf(Token::class);
    }

    function it_should_fail_if_authorization_header_has_no_bearer_token(Request $request)
    {
        $request->bearerToken()->willReturn();
        $this->shouldThrow(BearerTokenNotFound::class)->during('parse');
    }
}
