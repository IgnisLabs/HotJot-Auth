HotJot
======

HotJot is a low-level JSON WebToken authentication library. It is
framework agnostic, but it does come with some helpful integrations by
default. You can always develop your own, too.

It uses [`lcobucci/jwt`][1] library underneath. You may not need to
interact with it at all but it doesn't hurt to be a little bit familiar
with it.

Framework integrations will likely happen in a separate project.

Installation
------------

With composer:

```shell
$ composer require ignislabs/hotjot
```

Usage
-----

It seems more daunting than it really is. Yes, there are a bunch of
classes to instantiate and configure, but it's pretty straightforward.

So let's take care of the bootstrapping first, and then on how to
actually use all of this.

### Bootstrapping

You can interact with pretty much all of the classes on their own if
you need to, but you'll probably only need to interact with the Manager
and maybe the Factory.  
But the Manager can create, parse, refresh, blacklist, validate, and
verify tokens, so you'll probably spend more time with it.

We'll go over each class and then put them all together.

#### The Token Factory

The token factory is in charge of building an actual JWT from a set of
claims and, optionally, headers. It will need any signer from the
underlying JWT library and an encryption key.

The key used here will depend on the signer used.
If you used an HMAC signer, then it will be a simple string key, just
try to make it as secure/random as possible.  
But if you used an RSA or other public-key crypto signer, then you'll
need to generate a key pair and pass the **private** one here.

```php
<?php

$signer = new \Lcobucci\JWT\Signer\Hmac\Sha512();
$factory = new \IgnisLabs\HotJot\Token\Factory($signer, 'encription key');
```

#### Request Parser

The Request Parser will be the one responsible for parsing the token
out of the request headers.

Two parsers are provided by default: `lluminateRequestParser` and
`Psr7RequestParser`. Both need the current request and the parser from
the underlying library. This other parser just decodes an encoded JWT,
it doesn't interact with the request.

##### Illuminate (Laravel)

This one takes a `Illuminate\Http\Request` request object.

In Laravel it's easy to get the current request using `app('request')`
function or `$this->app->make('request')` from a service provider.

```php
<?php

$parser = new \IgnisLabs\HotJot\Parser\IlluminateRequestParser(
    app('request'),
    new \Lcobucci\JWT\Parser
);
```

##### PSR-7

This one takes a `Psr\Http\Message\RequestInterface` request object.

Here, getting the current request will depend on the framework (or
lack thereof) you're using.

```php
<?php

$parser = new \IgnisLabs\HotJot\Parser\Psr7RequestParser(
    $request,
    new \Lcobucci\JWT\Parser
);
```

#### Blacklist

The Blacklist is a really simple class that can, well, _blacklist_
tokens and check the blacklist for existing ones.

The provided implementation uses redis through the Predis library.

It also has an optional `keyPrefix` parameter that defaults to
`hotjot:blacklist`. The naming strategy for the key will be
`<prefix>:<jti>`..

A token will be blacklisted until it's `exp` date, that means that by
default, tokens will be blacklisted for at most 10 minutes.

Redis is great for this, but I might add a PSR-6 and/or PSR-16 version
so it can be swapped easily.

```php
<?php

$blacklist = new \IgnisLabs\HotJot\Blacklist\PredisBlacklist(
    $predisClient,
    'optional:key:prefix'
);
```

#### Verifier

The verifier is in charge of verifying the token signature. It's
unlikely that you'll ever need to interact with this one directly, but
when configuring there are some caveats that you need to be aware of.

It requires the same signer used for the Factory, since it will be it
that actually verifies the token. `lcobucci/jwt` signers do both
verification and signing (at least in the current version).

It also requires a key, and this one too will depend on the signer used.
If you used an HMAC signer, then it will be the same string encryption
key you used for the Factory.  
But if you used an RSA or other public-key crypto signer, then you'll
need to use the **public** one here.

```php
<?php

$verifier = new \IgnisLabs\HotJot\Token\Verifier(
    $signer,
    'encryption/public key'
);
```

#### Validator and Token Validators

The validator is an extremely simple class that takes a bunch of token
validators and uses them to validate a token.

These token validators need to implement the
`IgnisLabs\HotJot\Contracts\Token\Validator` contract.

This library already comes with some useful ones, but you can add as
many as you need.

```php
<?php

use IgnisLabs\HotJot\Token\Validators as 🕵;

$validator = new \IgnisLabs\HotJot\Validator(
    new 🕵\BlacklistValidator($blacklist), // fails if token is blacklisted
    new 🕵\TokenIdValidator, // fails if token doesn't have `jti` claim
    new 🕵\IssuedAtValidator, // fails if `iat` is in the future
    new 🕵\NotBeforeValidator, // fails if token used before `nbf`
    new 🕵\ExpiresAtValidator // fails if `exp` not set or is in the past
);
```

#### Manager

Now that we have all the moving parts ready, let's assemble them in the
Manager.

```php
<?php

$hotjot = new \IgnisLabs\HotJot\Manager(
    $factory,
    $requestParser,
    $blacklist,
    $verifier,
    $validator,
    15 // Allowed TTR (time-to-refresh) in days
);
```

### Usage

As said before the manager can do everything, it's a unified interface
for you to interact with this library.

But you can always use all these other classes on their own, and
sometimes it may even be convenient to do so.

So let's explore a few ways in which you can use this library.

#### Through the manager

The manager has several methods that serve as a shortcut to it's
dependencies, and all but one can be found on each them.

```php
<?php

// Create a token — same usage as Factory::create()
$token = $hotjot->create($claims, $headers);

// Parse token from request — same as Parser::parse()
$hotjot->parse();

// Check if a token is blacklisted — similar to Blacklist::has()
$hotjot->isBlacklisted($token);

// Blacklist a token ­— same as Blacklist::add()
$hotjot->blacklist($token);

// Validate a token — same as Validator::validate()
$hotjot->validate($token, ...$exludedValidatorClasses);

// Verify a token — same as Verifier::verify()
$hotjot->verify($token);
```

The one method that is manager-only is `refresh`, we'll see it in more
detail later on.

#### As separates

Some times you might not want to pull the manager.

TODO explain as separates

```php
<?php
// Create a token
$token = $factory->create($claims, $headers);

// Manually blacklist a token (the token must have `exp`)
$blacklist->add($token);

// Check if a token is blacklisted
$blacklist->has($token->id());

// To validate a token with all validators
$validator->validate($token);

// To validate a token excluding some validators:
$validator->validate($token,
    IssuedAtValidator::class,
    NotBeforeValidator::class
);
```

#### Token refreshing

TODO explain refreshing

```php
<?php
// Refresh an expired token
$hotjot->refresh($expiredToken);

// Refresh excluding validators
$hotjot->refresh($expiredToken, ...$exludedValidatorClasses);
```

[1]: https://github.com/lcobucci/jwt
