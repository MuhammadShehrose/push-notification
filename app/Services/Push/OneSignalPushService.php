<?php

namespace App\Services\Push;

use Illuminate\Support\Facades\Http;

class OneSignalPushService
{
    public function send(string|array $tokens, string $title, string $body, array $data = [], array $options = []): array
    {
        $tokens = is_array($tokens) ? $tokens : [$tokens];

        // Ensure "data" is always an associative array
        if (!is_array($data) || array_is_list($data)) {
            $data = ['info' => $data];
        }

        $payload = [
            'app_id' => config('services.onesignal.app_id'),
            'include_player_ids' => $tokens,
            'headings' => ['en' => $title],
            'contents' => ['en' => $body],
            'data' => $data,
        ];

        if (!empty($options['click_action'])) {
            $payload['url'] = $options['click_action'];
        }
        if (!empty($options['image'])) {
            $payload['big_picture'] = $options['image'];
        }

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . config('services.onesignal.api_key'),
            'Content-Type'  => 'application/json',
        ])->post('https://onesignal.com/api/v1/notifications', $payload);

        return $response->json();
    }
}
