<?php

return [

    'default' => env('PAYMENTS_TYPE', 'cash-in-hand'),

    'types' => [
        'cash-in-hand' => [
            'driver' => 'offline',
            'authorized' => 'pending',
        ],
        'card' => [
            'driver' => 'paystack',
            'authorized' => 'pending',
        ],
        'transfer' => [
            'driver' => 'teller',
            'authorized' => 'processing',
        ],
    ],

];
