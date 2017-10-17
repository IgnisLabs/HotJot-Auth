<?php

namespace spec\IgnisLabs\HotJot\Token\Validators;

use IgnisLabs\HotJot\Contracts\Token;
use IgnisLabs\HotJot\Exceptions\Validation\ClaimRequiredException;
use IgnisLabs\HotJot\Token\Validators\TokenIdValidator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TokenIdValidatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(TokenIdValidator::class);
    }

    function it_passes_validation_if_token_id_is_provided(Token $token)
    {
        $token->getClaim('jti')->willReturn('token id');
        $this->shouldNotThrow(\Exception::class)->duringValidate($token);
    }

    function it_requires_token_id(Token $token)
    {
        $token->getClaim('jti')->willReturn();
        $this->shouldThrow(ClaimRequiredException::class)->duringValidate($token);
    }
}
