<?php

return [
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
        'consumer_key' => env('XERO_CONSUMER_KEY', ''),
        'consumer_secret' => env('XERO_CONSUMER_SECRET', ''),

        /*
        |--------------------------------------------------------------------------
        | Xero endpoint for request token
        |--------------------------------------------------------------------------
        |
        | Request token endpoint for getting the Xero oauth token and oauth secret
        |
        | Important Note : Only change this value when this endpoint is changed.
        |
        */
        'request_token_endpoint' => 'https://api.xero.com/oauth/RequestToken',

        /*
        |--------------------------------------------------------------------------
        | Xero endpoint for authorization
        |--------------------------------------------------------------------------
        |
        | Authorization url (will get new oauth token and automatically replace
        | the old oauth token)
        |
        | Important Note : Only change this value when Xero authorization endpoint is changed.
        |
        */
        'authorization_endpoint' => 'https://api.xero.com/oauth/Authorize',

        /*
        |--------------------------------------------------------------------------
        | Xero endpoint for access token
        |--------------------------------------------------------------------------
        |
        | Authorization url for getting the Xero oauth token and oauth secret
        |
        | Important Note : Only change this value when this endpoint is changed.
        |
        */
        'access_token_endpoint' => 'https://api.xero.com/oauth/AccessToken',

        /*
       |--------------------------------------------------------------------------
       | Xero main endpoint for normal data
       |--------------------------------------------------------------------------
       |
       | normal data url for interacting with xero
       |
       | Important Note : Only change this value when this endpoint is changed.
       |
       */
        'xero_main_data_endpoint' => 'https://api.xero.com/api.xro/2.0/',

        /*
        |--------------------------------------------------------------------------
        | Signature Method
        |--------------------------------------------------------------------------
        |
        | Important Note : Right now only support HMAC-SHA1
        |
        */
        'oauth_signature_method' => 'HMAC-SHA1',

        /*
        |--------------------------------------------------------------------------
        | OAuth Version
        |--------------------------------------------------------------------------
        |
        */
        'oauth_version' => '1.0',


];
