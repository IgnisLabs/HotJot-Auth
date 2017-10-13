<?php

namespace spec\IgnisLabs\HotJot;

use Carbon\Carbon;
use IgnisLabs\HotJot\Contracts\Blacklist;
use IgnisLabs\HotJot\Contracts\RequestParser;
use IgnisLabs\HotJot\Contracts\Token\Factory;
use IgnisLabs\HotJot\Validator;
use IgnisLabs\HotJot\Exceptions\TokenCannotBeRefreshedException;
use IgnisLabs\HotJot\Manager;
use IgnisLabs\HotJot\Token;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    function let(Factory $factory, RequestParser $parser, Blacklist $blacklist, Validator $validator)
    {
        $this->beConstructedWith($factory, $parser, $blacklist, 15, $validator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    function it_should_create_a_new_token(Factory $factory, Token $token)
    {
        $claims = ['foo' => 'bar'];
        $headers = ['baz' => 'qux'];
        $factory->create($claims, $headers)->willReturn($token)->shouldBeCalled();

        $this->create($claims, $headers)->shouldBe($token);
    }

    function it_should_parse_token_from_request(RequestParser $parser, Token $token)
    {
        $parser->parse()->willReturn($token);
        $this->parse()->shouldBe($token);
    }

    function it_should_refresh_a_valid_token(Factory $factory, Token $oldToken, Token $newToken)
    {
        $oldToken->issuedAt()->willReturn(Carbon::parse('yesterday'));
        $oldToken->getClaims()->willReturn(['foo' => 'bar']);
        $oldToken->getHeaders()->willReturn(['baz' => 'qux']);

        $factory->create(['foo' => 'bar'], ['baz' => 'qux'])->willReturn($newToken);

        $this->refresh($oldToken)->shouldBe($newToken);
    }

    function it_should_not_refresh_a_token_past_time_to_refresh(Token $oldToken)
    {
        $oldToken->issuedAt()->willReturn(Carbon::parse('16 days ago'));
        $this->shouldThrow(TokenCannotBeRefreshedException::class)->duringRefresh($oldToken);
    }

    function it_should_check_if_token_is_blacklisted(Blacklist $blacklist, Token $token)
    {
        $token->id()->willReturn('token-id');
        $blacklist->has('token-id')->willReturn(true);
        $this->isBlacklisted($token)->shouldBe(true);
    }

    function it_should_add_token_to_blacklist(Blacklist $blacklist, Token $token)
    {
        $blacklist->add($token)->shouldBeCalled();
        $this->blacklist($token);
    }

    function it_should_validate_token(Validator $validator, Token $token)
    {
        $validator->validate($token)->shouldBeCalled();
        $this->validate($token);
    }
}
