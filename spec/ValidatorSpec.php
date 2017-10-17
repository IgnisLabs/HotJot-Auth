<?php

namespace spec\IgnisLabs\HotJot;

use IgnisLabs\HotJot\Contracts\Token\Token;
use IgnisLabs\HotJot\Contracts\Token\Validator as TokenValidator;
use IgnisLabs\HotJot\Validator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ValidatorSpec extends ObjectBehavior
{
    function let(TokenValidator $tokenValidator)
    {
        $this->beConstructedWith($tokenValidator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Validator::class);
    }

    function it_should_validate_a_token_using_all_validators(Token $token, TokenValidator $tokenValidator)
    {
        $tokenValidator->validate($token)->shouldBeCalled();
        $this->validate($token);
    }

    function it_should_be_able_to_exclude_validators_by_class_name(Token $token, TokenValidator $tokenValidator, ExcludedValidator $excludedValidator)
    {
        $tokenValidator->validate($token)->shouldBeCalled();
        $excludedValidator->validate($token)->shouldNotBeCalled();
        $this->addValidators($excludedValidator)->validate($token, get_class($excludedValidator->getWrappedObject()));
    }

    function it_should_add_validators_and_remain_immutable(TokenValidator $tokenValidator, AddedOrReplacedValidator $addedOrReplacedValidator, Token $token)
    {
        $tokenValidator->validate($token)->shouldBeCalled();
        $addedOrReplacedValidator->validate($token)->shouldBeCalled();

        $newValidator = $this->addValidators($addedOrReplacedValidator);
        $newValidator->shouldBeAnInstanceOf(Validator::class);
        $newValidator->shouldNotBe($this);
        $newValidator->validate($token);
    }

    function it_should_replace_validator_and_remain_immutable(TokenValidator $tokenValidator, AddedOrReplacedValidator $replacedValidator, Token $token)
    {
        $tokenValidator->validate($token)->shouldNotBeCalled();
        $replacedValidator->validate($token)->shouldBeCalled();

        $newValidator = $this->replaceValidators($replacedValidator);
        $newValidator->shouldBeAnInstanceOf(Validator::class);
        $newValidator->shouldNotBe($this);
        $newValidator->validate($token);
    }
}

// These guys here are just to make tests more readable, no need for an actual dummy implementation ;)
interface ExcludedValidator extends TokenValidator {}
interface AddedOrReplacedValidator extends TokenValidator {}
