<?php

namespace spec\IgnisLabs\HotJot\Blacklist;

use Carbon\Carbon;
use IgnisLabs\HotJot\Blacklist\PredisBlacklist;
use IgnisLabs\HotJot\Contracts\Token;
use PhpSpec\ObjectBehavior;
use Predis\ClientInterface;

class PredisBlacklistSpec extends ObjectBehavior {

    function let(PredisClientDouble $predis) {
        $this->beConstructedWith($predis);
    }

    function it_is_initializable() {
        $this->shouldHaveType(PredisBlacklist::class);
    }

    function it_can_blacklist_token(PredisClientDouble $predis, Token $token) {
        $exp = Carbon::now();
        $token->id()->willReturn('token-id');
        $token->expiresAt()->willReturn($exp);
        $token->__toString()->willReturn('a.valid.token');
        $predis->set('hotjot:blacklist:token-id', 'a.valid.token')->shouldBeCalled();
        $predis->expireat('hotjot:blacklist:token-id', $exp->getTimestamp())->willReturn(42)->shouldBeCalled();
        $this->add($token);
    }

    function it_can_check_if_token_is_blacklisted_by_id(PredisClientDouble $predis) {
        $predis->get('hotjot:blacklist:token-id')->willReturn('an.encoded.token');
        $this->has('token-id')->shouldBe(true);
    }
}

abstract class PredisClientDouble implements ClientInterface {
    function set($key, $value, $expireResolution = null, $expireTTL = null, $flag = null) {}
    function get($key) : string {}
    function expireat($key, $seconds) : int {}
}
