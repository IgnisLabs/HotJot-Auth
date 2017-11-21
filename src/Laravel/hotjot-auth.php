<?php

return [
    'signer' => [
        'alg' => 'HS256',
        'hmac' => [
            'encryption_key' => env('HOTJOT_ENCRYPTION_KEY', '')
        ],
        'rsa' => [
            'private_key' => env('HOTJOT_PRIVATE_KEY', 'path/to/private-key.pem'),
            'public_key' => env('HOTJOT_PUBLIC_KEY', 'path/to/public-key.pem'),
            'passphrase' => env('HOTJOT_KEY_PASSPHRASE', ''),
        ],
    ],
    'token' => [
        'ttl' => 10, // in minutes
        'ttr' => 15, // in days
        'id_generator' => \IgnisLabs\HotJot\Auth\Token\RandomBytesIdGenerator::class,
        'user_identifier_claim' => 'sub',
        'default_claims' => [
            'iss' => 'http://api.example.com',
            'aud' => 'http://www.example.com',
        ],
        'validators' => [
            // \Your\Own\Validator::class
        ],
    ],
    'blacklist' => [
        'key_prefix' => 'hotjot:blacklist',
    ],
];
