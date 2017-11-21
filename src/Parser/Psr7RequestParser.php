<?php

namespace IgnisLabs\HotJot\Auth\Parser;

use IgnisLabs\HotJot\Parser;
use IgnisLabs\HotJot\Token;
use IgnisLabs\HotJot\Auth\Exceptions\AuthorizationHeaderNotFound;
use IgnisLabs\HotJot\Auth\Exceptions\BearerTokenNotFound;
use IgnisLabs\HotJot\Auth\Contracts\RequestParser;
use Psr\Http\Message\RequestInterface;

class Psr7RequestParser implements RequestParser {

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var Parser
     */
    private $parser;

    /**
     * IlluminateRequestParser constructor.
     * @param RequestInterface $request
     * @param Parser           $parser
     */
    public function __construct(RequestInterface $request, Parser $parser) {
        $this->request = $request;
        $this->parser = $parser;
    }

    /**
     * Parse token from current request
     * @return Token|null
     */
    public function parse() : ?Token {
        $authHeader = $this->request->getHeaderLine('authorization');

        if (!$authHeader) {
            throw new AuthorizationHeaderNotFound;
        }

        if (preg_match('/^Bearer (.*)$/i', $authHeader, $matches)) {
            return $this->parser->parse($matches[1]);
        }

        return null;
    }
}
