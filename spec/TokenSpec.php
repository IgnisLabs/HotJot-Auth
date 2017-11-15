<?php

namespace spec\IgnisLabs\HotJot\Auth;

use Carbon\Carbon;
use IgnisLabs\HotJot\Auth\Token;
use Lcobucci\JWT\Token as LcobucciToken;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TokenSpec extends ObjectBehavior
{
    function let(LcobucciToken $token)
    {
        $this->beConstructedWith($token);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Token::class);
    }

    function it_should_get_id(LcobucciToken $token)
    {
        $token->getClaim('jti')->willReturn('token-id');
        $this->id()->shouldBe('token-id');
    }

    function it_should_get_subject(LcobucciToken $token)
    {
        $token->getClaim('sub')->willReturn('subject');
        $this->subject()->shouldBe('subject');
    }

    function it_should_get_issued_by(LcobucciToken $token)
    {
        $token->getClaim('iss')->willReturn('issued by');
        $this->issuedBy()->shouldBe('issued by');
    }

    function it_should_get_audience(LcobucciToken $token)
    {
        $token->getClaim('aud')->willReturn('audience');
        $this->audience()->shouldBe('audience');
    }

    function it_should_get_issued_at_date(LcobucciToken $token)
    {
        $timestamp = Carbon::now()->timestamp;
        $token->getClaim('iat')->willReturn($timestamp);
        $issuedAt = $this->issuedAt();
        $issuedAt->shouldBeAnInstanceOf(\DateTimeInterface::class);
        $issuedAt->timestamp->shouldBe($timestamp);
    }

    function it_should_get_not_before_date(LcobucciToken $token)
    {
        $timestamp = Carbon::now()->timestamp;
        $token->getClaim('nbf')->willReturn($timestamp);
        $notBefore = $this->notBefore();
        $notBefore->shouldBeAnInstanceOf(\DateTimeInterface::class);
        $notBefore->timestamp->shouldBe($timestamp);
    }

    function it_should_get_expires_at_date(LcobucciToken $token)
    {
        $timestamp = Carbon::now()->timestamp;
        $token->getClaim('exp')->willReturn($timestamp);
        $expiresAt = $this->expiresAt();
        $expiresAt->shouldBeAnInstanceOf(\DateTimeInterface::class);
        $expiresAt->timestamp->shouldBe($timestamp);
    }

    function it_should_get_the_signature(LcobucciToken $token)
    {
        $token->__toString()->willReturn('foo.bar.baz');
        $this->signature()->shouldBe('baz');
    }

    function it_should_get_any_claim_as_it_is(LcobucciToken $token)
    {
        $token->getClaim('foo')->willReturn('bar');
        $this->getClaim('foo')->shouldBe('bar');
    }

    function it_should_get_all_claims(LcobucciToken $token)
    {
        $token->getClaims()->willReturn(['foo' => 'bar', 'baz' => 'qux']);
        $this->getClaims()->shouldBe(['foo' => 'bar', 'baz' => 'qux']);
    }

    function it_should_get_any_header_as_it_is(LcobucciToken $token)
    {
        $token->getHeader('foo')->willReturn('bar');
        $this->getHeader('foo')->shouldBe('bar');
    }

    function it_should_get_all_headers(LcobucciToken $token)
    {
        $token->getHeaders()->willReturn(['foo' => 'bar', 'baz' => 'qux']);
        $this->getHeaders()->shouldBe(['foo' => 'bar', 'baz' => 'qux']);
    }
}
