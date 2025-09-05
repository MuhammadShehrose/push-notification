<?php

return [
    'api_key'            => env('FIREBASE_API_KEY'),
    'auth_domain'        => env('FIREBASE_AUTH_DOMAIN'),
    'project_id'         => env('FIREBASE_PROJECT_ID'),
    'storage_bucket'     => env('FIREBASE_STORAGE_BUCKET'),
    'messaging_sender_id'=> env('FCM_SENDER_ID'),
    'app_id'             => env('FIREBASE_APP_ID'),
    'measurement_id'     => env('FIREBASE_MEASUREMENT_ID'),
    'vapid_public_key'   => env('VAPID_PUBLIC_KEY'),
];
