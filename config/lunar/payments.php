<?php

return [

    'default' => env('PAYMENTS_TYPE', 'cash-in-hand'),

    'types' => [
        'cash-in-hand' => [
            'driver' => 'offline',
            'authorized' => 'payment-offline',
        ],
        'transfer' => [
            'driver' => 'bank-transfer',
            'authorized' => 'waiting-payment',
            'banks' => [['name' => 'Zenith Bank', 'account-number' => '2002943194', 'account-name' => 'Ikpugbu George']],
        ],
        'card' => [
            'driver' => 'paystack',
            'authorized' => 'waiting-payment',
            'url' => env('PAYSTACK_PAYMENT_URL', 'https://api.paystack.co'),
            'public' => env('PAYSTACK_TEST_PUBLIC_KEY', 'use your public key here'),
            'secret' => env('PAYSTACK_TEST_SECRET_KEY', 'use your secret key here'),
            'sandbox' => true,
        ],
    ],

];
