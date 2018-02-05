<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OAuth
    |--------------------------------------------------------------------------
    */
    'oauth' => [
        /*
        |--------------------------------------------------------------------------
        | Callback URL
        |--------------------------------------------------------------------------
        |
        | Provide a callback URL
        |
        */
        'callback' => env('XERO_CALLBACK', ''),

        /*
        |--------------------------------------------------------------------------
        | Xero application authentication
        |--------------------------------------------------------------------------
        |
        | Before using this service, you'll need to register an applicatin via
        | the Xero developer website. When setting up your application, take
        | note of the consumer key and shared secret, as well as the
        | application type (this package support for public only).
        |
        */
        'consumer_key' => env('XERO_CUSTOMER_KEY', ''),
        'consumer_secret' => env('XERO_CUSTOMER_SECRET', ''),

    ],
];
