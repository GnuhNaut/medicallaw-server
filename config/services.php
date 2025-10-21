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
    'vietqr' => [
        'api_url' => env('VIETQR_API_URL', 'https://api.vietqr.io'),
        'client_id' => env('VIETQR_CLIENT_ID'),
        'api_key' => env('VIETQR_API_KEY'),
        'bin' => env('VIETQR_BIN'),
        'account_no' => env('VIETQR_ACCOUNT_NO'),
        'account_name' => env('VIETQR_ACCOUNT_NAME'),
        'webhook_user' => env('VIETQR_WEBHOOK_USER'),
        'webhook_pass' => env('VIETQR_WEBHOOK_PASS'),
        'price_ticket' => env('VIETQR_PRICE_TICKET'),
    ],
    
    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
