HotJot
======

HotJot is a low-level JSON WebToken authentication library for APIs.

> It is framework agnostic, but it does come with Laravel integration
> by default.

It works with [`ignislabs/hotjot`][1] for JWT management, adding only a
few classes around it. You'll need to be familiar with it, go and read
it's [documentation][1] first.

Installation
------------

With composer:

```shell
$ composer require ignislabs/hotjot-auth
```

### Laravel

Even though it can be used with any framework (or none), the Laravel
integration comes out-of-the-box.

#### Package Configuration

When you require this package on Laravel 5.5, it will automatically
discover the service provider, so you only need to publish and modify
the configuration accordingly:

```bash
$ php artisan vendor:publish --provider="IgnisLabs\HotJot\Auth\Laravel\HotJotAuthServiceProvider"
```

Now you'll have the new config file `config/hotjot-auth.php`.

You can leave it as it is or customize to your liking, it's very
straightforward. You just need to set the necessary environment
variables in your `.env` file.

By default it will use the `HS256` algorithm.

#### Auth Configuration

Now you need to update Laravel's auth config by setting
`guards.api.driver` to `hotjot`. You can leave the `provider` as is.

Usage
-----

[1]: https://github.com/IgnisLabs/HotJot
