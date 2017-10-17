<?php

namespace spec\IgnisLabs\HotJot\Token\Validators;

use IgnisLabs\HotJot\Contracts\Token;
use IgnisLabs\HotJot\Exceptions\Validation\ClaimRequiredException;
use IgnisLabs\HotJot\Token\Validators\IssuerValidator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IssuerValidatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(IssuerValidator::class);
    }

    function it_passes_validation_if_issuer_is_provided(Token $token)
    {
        $token->getClaim('iss')->willReturn('issuer');
        $this->shouldNotThrow(\Exception::class)->duringValidate($token);
    }

    function it_requires_issuer(Token $token)
    {
        $token->getClaim('iss')->willReturn();
        $this->shouldThrow(ClaimRequiredException::class)->duringValidate($token);
    }
}
