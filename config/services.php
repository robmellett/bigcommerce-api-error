<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    // We want to stick with the BC generated Store URL, over https://staging.adorebeauty.com.au
    // as there has been DNS blockers
    'big_commerce' => [
        'store_url' => env('BIG_COMMERCE_STORE_URL', 'https://store-5pecd49xfo.mybigcommerce.com'),
        'api' => env('BIG_COMMERCE_API_PATH'),
        'client_id' => env('BIG_COMMERCE_CLIENT_ID'),
        'client_secret' => env('BIG_COMMERCE_CLIENT_SECRET'),
        'auth_client' => env('BIG_COMMERCE_AUTH_CLIENT'),
        'auth_token' => env('BIG_COMMERCE_AUTH_TOKEN'),
        'store_hash' => env('BIG_COMMERCE_STORE_HASH'),

        // Used in Web to append the redirect url to the JWT
        'redirect_url' => env('BIG_COMMERCE_CART_REDIRECT_URL'),

        // This is so BigCommerce can communicate with Vapor securely via webhooks.
        'webhook_key' => env('BIG_COMMERCE_WEBHOOK_KEY'),
        'webhook_secret' => env('BIG_COMMERCE_WEBHOOK_SECRET'),
    ],

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

];
