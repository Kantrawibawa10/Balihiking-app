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

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    /*
    |--------------------------------------------------------------------------
    | GOOGLE OAUTH
    |--------------------------------------------------------------------------
    */

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),

        'client_secret' => env('GOOGLE_CLIENT_SECRET'),

        'redirect' => env(
            'GOOGLE_REDIRECT_URI',
            env('APP_URL') . '/auth/google/callback'
        ),

        /*
         * LOCAL DEVELOPMENT ONLY:
         *
         * false = abaikan validasi CA SSL
         * true  = validasi SSL normal
         *
         * Production WAJIB true.
         */
        'ssl_verify' => filter_var(
            env('GOOGLE_OAUTH_SSL_VERIFY', true),
            FILTER_VALIDATE_BOOL
        ),
    ],

];