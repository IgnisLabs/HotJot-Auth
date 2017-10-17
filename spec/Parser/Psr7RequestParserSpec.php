<?php

namespace spec\IgnisLabs\HotJot\Parser;

use IgnisLabs\HotJot\Contracts\Token\Token;
use IgnisLabs\HotJot\Exceptions\AuthorizationHeaderNotFound;
use IgnisLabs\HotJot\Exceptions\BearerTokenNotFound;
use IgnisLabs\HotJot\Parser\Psr7RequestParser;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Token as LcobucciToken;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;

class Psr7RequestParserSpec extends ObjectBehavior
{
    function let(RequestInterface $request, Parser $parser)
    {
        $this->beConstructedWith($request, $parser);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Psr7RequestParser::class);
    }

    function it_should_parse_token_from_request(RequestInterface $request, Parser $parser, LcobucciToken $token)
    {
        $request->getHeaderLine('authorization')->willReturn('Bearer a.valid.token');
        $parser->parse('a.valid.token')->shouldBeCalled()->willReturn($token);
        $this->parse()->shouldBeAnInstanceOf(Token::class);
    }

    function it_should_fail_if_request_has_no_authorization_header(RequestInterface $request)
    {
        $request->getHeaderLine('authorization')->willReturn();
        $this->shouldThrow(AuthorizationHeaderNotFound::class)->during('parse');
    }

    function it_should_fail_if_authorization_header_has_no_bearer_token(RequestInterface $request)
    {
        $request->getHeaderLine('authorization')->willReturn('not a valid bearer token');
        $this->shouldThrow(BearerTokenNotFound::class)->during('parse');
    }
}
