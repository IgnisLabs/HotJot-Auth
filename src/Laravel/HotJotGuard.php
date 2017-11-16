<?php

namespace IgnisLabs\HotJot\Auth\Laravel;

use IgnisLabs\HotJot\Auth\Contracts\RequestParser;
use IgnisLabs\HotJot\Auth\Contracts\Token\Factory;
use IgnisLabs\HotJot\Token;
use IgnisLabs\HotJot\Validator;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;

class HotJotGuard implements Guard {
    use GuardHelpers;

    /**
     * @var Factory
     */
    private $factory;

    /**
     * @var RequestParser
     */
    private $parser;

    /**
     * @var string
     */
    private $userIdentifierClaim;

    public function __construct(Factory $factory, RequestParser $parser, UserProvider $userProvider, string $userIdentifierClaim = 'sub') {
        $this->factory = $factory;
        $this->parser = $parser;
        $this->setProvider($userProvider);
        $this->userIdentifierClaim = $userIdentifierClaim;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return Authenticatable|null
     */
    public function user() {
        if (!$this->user) {
            $token = $this->parser->parse();
            $this->user = $this->getProvider()->retrieveById($token->getClaim($this->userIdentifierClaim));
        }

        return $this->user;
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array $credentials
     * @return bool
     */
    public function validate(array $credentials = []) {
        $this->user = $user = $this->getProvider()->retrieveByCredentials($credentials);
        return $this->hasValidCredentials($user, $credentials);
    }

    /**
     * Attempt to authenticate a user using the given credentials.
     *
     * @param  array  $credentials
     * @return Token|false JSON Web Token
     */
    public function attempt(array $credentials = []) {
        if ($this->validate($credentials)) {
            return $this->factory->create([
                $this->userIdentifierClaim => $this->user()->getAuthIdentifier(),
            ]);
        }

        return false;
    }

    /**
     * Determine if the user matches the credentials.
     *
     * @param  mixed  $user
     * @param  array  $credentials
     * @return bool
     */
    protected function hasValidCredentials($user, $credentials) {
        return ! is_null($user) && $this->provider->validateCredentials($user, $credentials);
    }
}
