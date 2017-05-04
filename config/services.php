<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'facebook' => [
        'client_id' => '1716972055254456',
        'client_secret' => '7f10a379a37eaab59f301f1044913ef0',
        'redirect' => PHP_SAPI === 'cli' ? false :url('auth/callback/facebook'),
    ],
    'instagram' => [
        'client_id' => 'e02b888747284f44a429461641d5e5ab',
        'client_secret' => 'dcb6b07882f44c10a477190737c22ed0',
        'redirect' => PHP_SAPI === 'cli' ? false :url('auth/callback/instagram'),
    ],
    'twitter' => [
        'client_id' => 'Y8D1RKtoYa6cF6UNKw9JvExxi',
        'client_secret' => 'O6cQkODMpv7VHRsXxWkjDxhMaKi0cJVYiRXv5ODGm8VhWZpeu4',
        'redirect' => PHP_SAPI === 'cli' ? false : url('auth/callback/twitter'),
    ],
];

