<?php

namespace IgnisLabs\HotJot\Auth\Laravel;

use IgnisLabs\HotJot\Auth\Blacklist\PredisBlacklist;
use IgnisLabs\HotJot\Auth\Contracts\Blacklist;
use IgnisLabs\HotJot\Auth\Contracts\RequestParser;
use IgnisLabs\HotJot\Auth\Contracts\Token\Factory as FactoryContract;
use IgnisLabs\HotJot\Auth\Contracts\Token\Refresher as RefresherContract;
use IgnisLabs\HotJot\Auth\Contracts\Token\Verifier as VerifierContract;
use IgnisLabs\HotJot\Auth\Parser\IlluminateRequestParser;
use IgnisLabs\HotJot\Auth\Token\Factory;
use IgnisLabs\HotJot\Auth\Token\Refresher;
use IgnisLabs\HotJot\Auth\Token\Validators as 🕵;
use IgnisLabs\HotJot\Auth\Token\Verifier;
use IgnisLabs\HotJot\Contracts\Signer;
use IgnisLabs\HotJot\Parser;
use IgnisLabs\HotJot\Support\Encoder;
use IgnisLabs\HotJot\Validator;
use IgnisLabs\HotJot\Validators as 🕵🕵;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class HotJotAuthServiceProvider extends ServiceProvider {

    public function boot() {
        $this->publishes([
            __DIR__ . '/hotjot-auth.php' => config_path('hotjot-auth.php'),
        ]);
    }

    public function register() {
        $this->registerSigner();
        $this->registerFactory();
        $this->registerRequestParser();
        $this->registerBlacklist();
        $this->registerVerifier();
        $this->registerValidator();
        $this->registerRefresher();
        $this->registerGuard();
    }

    private function registerSigner() {
        $this->app->singleton(Signer::class, function() {
            $signerClass = '\\IgnisLabs\\HotJot\\Signer\\';
            $alg = config('hotjot-auth.signer.alg');

            if (starts_with($alg, 'HS')) {
                $signerClass .= 'HMAC\\' . $alg;
                return new $signerClass(config('hotjot-auth.signer.hmac.encryption_key'));
            } elseif (starts_with($alg, 'RS')) {
                $signerClass .= 'RSA\\' . $alg;
                $privateKey = file_get_contents(config('hotjot-auth.signer.rsa.private_key'));
                $publicKey = file_get_contents(config('hotjot-auth.signer.rsa.public_key'));
                return new $signerClass($privateKey, $publicKey, config('hotjot-auth.signer.rsa.passphrase'));
            }
        });

        $this->app->alias(Signer::class, 'hotjot.auth.signer');
    }

    private function registerFactory() {
        $this->app->singleton(FactoryContract::class, function() {
            $idGenerator = config('hotjot-auth.token.id_generator');

            return new Factory(
                new \IgnisLabs\HotJot\Factory($this->app->make('hotjot.auth.signer'), new Encoder),
                new $idGenerator,
                config('hotjot-auth.token.default_claims', []),
                config('hotjot-auth.token.ttl', 10)
            );
        });

        $this->app->alias(FactoryContract::class, 'hotjot.auth.factory');
    }

    private function registerRequestParser() {
        $this->app->singleton(RequestParser::class, function() {
            return new IlluminateRequestParser($this->app->make('request'), new Parser());
        });

        $this->app->alias(RequestParser::class, 'hotjot.auth.parser');
    }

    private function registerBlacklist() {
        $this->app->singleton(Blacklist::class, function() {
            return new PredisBlacklist(
                $this->app->make('redis.connection')->client(),
                config('hotjot-auth.blacklist.key_prefix')
            );
        });

        $this->app->alias(Blacklist::class, 'hotjot.auth.blacklist');
    }

    private function registerVerifier() {
        $this->app->singleton(VerifierContract::class, function() {
            return new Verifier($this->app->make('hotjot.auth.signer'));
        });

        $this->app->alias(VerifierContract::class, 'hotjot.auth.verifier');
    }

    private function registerValidator() {
        $this->app->singleton(Validator::class, function() {
            $customValidators = array_map(function($validator) {
                return $this->app->make($validator);
            }, config('hotjot-auth.token.validators', []));

            return new Validator(
                // HotJot Auth Validators
                new 🕵\IsBlacklistedValidator($this->app->make('hotjot.auth.blacklist')),
                new 🕵\TokenIdValidator(),
                // HotJot Validators
                new 🕵🕵\IssuedAtValidator(true),
                new 🕵🕵\NotBeforeValidator(true),
                new 🕵🕵\ExpiresAtValidator(true),
                ...$customValidators
            );
        });

        $this->app->alias(Validator::class, 'hotjot.auth.validator');
    }

    private function registerRefresher() {
        $this->app->singleton(RefresherContract::class, function() {
            return new Refresher(
                $this->app->make('hotjot.auth.verifier'),
                $this->app->make('hotjot.auth.validator'),
                $this->app->make('hotjot.auth.blacklist'),
                $this->app->make('hotjot.auth.factory'),
                config('hotjot-auth.token.ttr')
            );
        });

        $this->app->alias(RefresherContract::class, 'hotjot.auth.refresher');
    }

    private function registerGuard() {
        Auth::extend('hotjot', function ($app, $name, array $config) {
            return new HotJotGuard(
                $app->make('hotjot.auth.factory'),
                $app->make('hotjot.auth.parser'),
                Auth::createUserProvider($config['provider']),
                config('hotjot-auth.token.user_identifier_claim')
            );
        });
    }
}
