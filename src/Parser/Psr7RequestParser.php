<?php

namespace IgnisLabs\HotJot\Parser;

use IgnisLabs\HotJot\Exceptions\AuthorizationHeaderNotFound;
use IgnisLabs\HotJot\Exceptions\BearerTokenNotFound;
use Lcobucci\JWT\Parser;
use IgnisLabs\HotJot\Token;
use IgnisLabs\HotJot\Contracts\RequestParser;
use IgnisLabs\HotJot\Contracts\Token\Token as TokenContract;
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
     * @return TokenContract
     */
    public function parse() : TokenContract {
        $authHeader = $this->request->getHeaderLine('authorization');

        if (!$authHeader) {
            // @todo create custom exception
            throw new AuthorizationHeaderNotFound;
        }

        if (!preg_match('/^Bearer (.*)$/i', $authHeader, $matches)) {
            throw new BearerTokenNotFound;
        }

        return new Token($this->parser->parse($matches[1]));
    }
}
