<?php

namespace IgnisLabs\HotJot\Auth\Parser;

use IgnisLabs\HotJot\Parser;
use IgnisLabs\HotJot\Token;
use IgnisLabs\HotJot\Auth\Contracts\RequestParser;
use IgnisLabs\HotJot\Auth\Exceptions\BearerTokenNotFound;
use Illuminate\Http\Request;

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
     * @param Parser  $parser
     */
    public function __construct(Request $request, Parser $parser) {
        $this->request = $request;
        $this->parser = $parser;
    }

    /**
     * Parse token from current request
     * @return Token
     */
    public function parse() : Token {
        if (!$jwt = $this->request->bearerToken()) {
            throw new BearerTokenNotFound;
        }

        return $this->parser->parse($jwt);
    }
}
