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
    'google_cloud' => [
        'project_id' => env('GOOGLE_CLOUD_PROJECT_ID'),
        'bucket'     => env('GOOGLE_CLOUD_STORAGE_BUCKET'),
        'key_file'   => env('GOOGLE_CLOUD_KEY_PATH'), // storage_path('app/credentials/snap-csg-229610-f2bb5c5c6b21.json'),
    ],

    'oracle' => [
        'username' => env('API_USERNAME'),
        'password' => env('API_PASSWORD'),
        'url'      => 'https://fa-evjn-saasfaprod1.fa.ocs.oraclecloud.com/fscmRestApi/resources/11.13.18.05/'
    ],
];
