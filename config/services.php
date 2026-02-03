<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resayil WhatsApp API
    |--------------------------------------------------------------------------
    */
    'resayil' => [
        'base_url' => env('RESAYIL_BASE_URL', 'https://wa.resayil.io/api/v1'),
        'api_key' => env('RESAYIL_API_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | MyFatoorah Payment Gateway
    |--------------------------------------------------------------------------
    */
    'myfatoorah' => [
        'base_url' => env('MYFATOORAH_BASE_URL', 'https://apitest.myfatoorah.com'),
        'api_key' => env('MYFATOORAH_API_KEY'),
        'test_mode' => env('MYFATOORAH_TEST_MODE', true),
        'country_code' => env('MYFATOORAH_COUNTRY_CODE', 'KWT'),
    ],

    /*
    |--------------------------------------------------------------------------
    | OTP Configuration
    |--------------------------------------------------------------------------
    */
    'otp' => [
        'expiry_minutes' => env('OTP_EXPIRY_MINUTES', 10),
        'max_attempts' => env('OTP_MAX_ATTEMPTS', 5),
        'resend_cooldown_seconds' => env('OTP_RESEND_COOLDOWN_SECONDS', 60),
    ],

];
