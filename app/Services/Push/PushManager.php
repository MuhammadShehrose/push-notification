<?php

namespace App\Services\Push;

use InvalidArgumentException;

class PushManager
{
    public function __construct(
        private FcmPushService $fcm,
        private OneSignalPushService $onesignal,
    ) {}

    public function send(string|array $tokens, string $title, string $body, array $data = [], array $options = []): array
    {
        $driver = config('app.push_driver', 'fcm');

        return match ($driver) {
            'fcm'       => $this->fcm->send($tokens, $title, $body, $data, $options),
            'onesignal' => $this->onesignal->send($tokens, $title, $body, $data, $options),
            default     => throw new InvalidArgumentException("Unsupported push driver: $driver"),
        };
    }
}
