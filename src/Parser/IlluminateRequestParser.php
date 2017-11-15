<?php

namespace IgnisLabs\HotJot\Auth\Parser;

use IgnisLabs\HotJot\Auth\Exceptions\BearerTokenNotFound;
use Illuminate\Http\Request;
use Lcobucci\JWT\Parser;
use IgnisLabs\HotJot\Auth\Token;
use IgnisLabs\HotJot\Auth\Contracts\RequestParser;
use IgnisLabs\HotJot\Auth\Contracts\Token as TokenContract;

class IlluminateRequestParser implements RequestParser {

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Parser
     */
    private $parser;

    /**
     * IlluminateRequestParser constructor.
     * @param Request $request
     */
    public function __construct(Request $request, Parser $parser) {
        $this->request = $request;
        $this->parser = $parser;
    }

    /**
     * Parse token from current request
     * @return TokenContract
     */
    public function parse() : TokenContract {
        $jwt = $this->request->bearerToken();

        if (!$jwt) {
            throw new BearerTokenNotFound;
        }

        return new Token($this->parser->parse($jwt));
    }
}
