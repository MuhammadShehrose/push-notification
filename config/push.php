<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Push Driver
    |--------------------------------------------------------------------------
    |
    | Supported: "fcm", "apn", "webpush"
    | You can change this via .env (PUSH_DRIVER=fcm)
    |
    */
    'default' => env('PUSH_DRIVER', 'fcm'),

    /*
    |--------------------------------------------------------------------------
    | Firebase Cloud Messaging (FCM)
    |--------------------------------------------------------------------------
    */
    'fcm' => [
        'credentials' => env('FCM_CREDENTIALS'),
        'sender_id'   => env('FCM_SENDER_ID'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Apple Push Notification (APN)
    |--------------------------------------------------------------------------
    */
    'apn' => [
        'key_id'     => env('APN_KEY_ID'),
        'team_id'    => env('APN_TEAM_ID'),
        'app_bundle' => env('APN_APP_BUNDLE'),
        'private_key'=> env('APN_PRIVATE_KEY'), // file path or base64 encoded
        'environment'=> env('APN_ENV', 'sandbox'), // sandbox or production
    ],

    /*
    |--------------------------------------------------------------------------
    | Web Push (Browser Notifications)
    |--------------------------------------------------------------------------
    */
    'webpush' => [
        'public_key'  => env('VAPID_PUBLIC_KEY'),
        'private_key' => env('VAPID_PRIVATE_KEY'),
        'subject'     => env('VAPID_SUBJECT', 'mailto:admin@example.com'),
    ],

];
