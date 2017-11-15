<?php

namespace spec\IgnisLabs\HotJot\Auth;

use Carbon\Carbon;
use IgnisLabs\HotJot\Auth\Contracts\Blacklist;
use IgnisLabs\HotJot\Auth\Contracts\RequestParser;
use IgnisLabs\HotJot\Auth\Contracts\Token\Factory;
use IgnisLabs\HotJot\Auth\Contracts\Token\Verifier;
use IgnisLabs\HotJot\Auth\Exceptions\SignatureVerificationFailedException;
use IgnisLabs\HotJot\Auth\Exceptions\Validation\ValidationException;
use IgnisLabs\HotJot\Auth\Token\Validators\ExpiresAtValidator;
use IgnisLabs\HotJot\Auth\Validator;
use IgnisLabs\HotJot\Auth\Exceptions\TokenCannotBeRefreshedException;
use IgnisLabs\HotJot\Auth\Manager;
use IgnisLabs\HotJot\Auth\Token;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    function let(Factory $factory, RequestParser $parser, Blacklist $blacklist, Verifier $verifier, Validator $validator)
    {
        $this->beConstructedWith($factory, $parser, $blacklist, $verifier, $validator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    function it_should_create_a_new_token(Factory $factory, Token $token)
    {
        $claims = ['foo' => 'bar'];
        $headers = ['baz' => 'qux'];
        $factory->create($claims, $headers, null)->willReturn($token)->shouldBeCalled();

        $this->create($claims, $headers)->shouldBe($token);
    }

    function it_should_parse_token_from_request(RequestParser $parser, Token $token)
    {
        $parser->parse()->willReturn($token);
        $this->parse()->shouldBe($token);
    }

    function it_should_refresh_a_token_from_a_valid_old_one(Factory $factory, Blacklist $blacklist, Verifier $verifier, Validator $validator, Token $oldToken, Token $newToken)
    {
        $oldToken->id()->willReturn('foo');
        $oldToken->issuedAt()->willReturn(Carbon::parse('yesterday'));
        $oldToken->getClaims()->willReturn(['foo' => 'bar']);
        $oldToken->getHeaders()->willReturn(['baz' => 'qux']);

        $validator->validate($oldToken, ExpiresAtValidator::class)->shouldBeCalled();
        $verifier->verify($oldToken)->shouldBeCalled();
        $blacklist->add($oldToken)->shouldBeCalled();

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

    function it_should_blacklist_token_if_validation_fails(Blacklist $blacklist, Validator $validator, Token $token)
    {
        $validator->validate($token)->willThrow(ValidationException::class);
        $blacklist->add($token)->shouldBeCalled();
        $this->shouldThrow(ValidationException::class)->duringValidate($token);
    }

    function it_should_verify_token(Verifier $verifier, Token $token)
    {
        $verifier->verify($token)->shouldBeCalled();
        $this->verify($token);
    }

    function it_should_blacklist_token_if_verification_fails(Blacklist $blacklist, Verifier $verifier, Token $token)
    {
        $verifier->verify($token)->willThrow(SignatureVerificationFailedException::class);
        $blacklist->add($token)->shouldBeCalled();
        $this->shouldThrow(SignatureVerificationFailedException::class)->duringVerify($token);
    }
}
