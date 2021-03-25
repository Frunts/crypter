<?php
return [

    'default' => 'aes128',

    'drivers' => [
        'aes128' => [
            'crypt_svs' => 'AES',
            'crypt_type' => 'kv',
            'crypt_method' => 'AES-128-CBC',
            'kv' => 'default-128'
        ],
        'ase256' => [
            'crypt_svs' => 'AES',
            'crypt_type' => 'kv',
            'crypt_method' => 'AES-256-CBC',
            'kv' => 'default-256'
        ],
        'rsa' => [
            'crypt_svs' => 'RSA',
            'crypt_type' => 'crets',
            'crypt_method' => 'RSA',
            'crets_type' => 'file', //[file, content]
            'crets_bag' => 'default'
        ]
    ],

    'kvs' => [
        'default-128' => [
            'key' => env('CRYPTER_AES_128_KEY', 'ZXHKdIvOHuuRtjnD'),
            'iv' => env('CRYPTER_AES_128_IV', 'fFHmavOFyZBGa+g=')
        ],
        'default-256' => [
            'key' => env('CRYPTER_AES_256_KEY', 'wLB9tg2I1c+SnWvh'),
            'iv' => env('CRYPTER_AES_256_IV', 'fFHmavOFyZBGa+g=')
        ]
    ],

    'crets_bags' => [
        'default' => [
            'publicKey' => env('CRYPTER_RSA_PUBLIC_KEY', ''),
            'privateKey' => env('CRYPTER_RSA_PRIVATE_KEY', '')
        ],
    ]
        
];