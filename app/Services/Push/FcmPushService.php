<?php

namespace App\Services\Push;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\WebPushConfig;

class FcmPushService
{
    public function __construct(private Messaging $messaging) {}

    public static function makeFromConfig(): self
    {
        $factory = (new Factory)->withServiceAccount(config('services.firebase.credentials'));
        return new self($factory->createMessaging());
    }

    /**
     * Send to one or many tokens.
     *
     * @param  string|array  $tokens
     * @param  string        $title
     * @param  string        $body
     * @param  array         $data
     * @param  array         $options ['click_action' => url, 'image' => url]
     * @return array
     */
    public function send(string|array $tokens, string $title, string $body, array $data = [], array $options = []): array
    {
        $tokens = is_array($tokens) ? array_values(array_filter($tokens)) : [$tokens];

        // Ensure data values are strings
        $stringData = [];
        foreach ($data as $k => $v) {
            $stringData[$k] = is_scalar($v) ? (string) $v : json_encode($v, JSON_UNESCAPED_UNICODE);
        }

        $notification = Notification::create($title, $body, $options['image'] ?? null);

        $message = CloudMessage::new()
            ->withNotification($notification)
            ->withData($stringData)
            ->withWebPushConfig(
                WebPushConfig::fromArray([
                    'headers' => ['Urgency' => 'high'],
                    'fcm_options' => ['link' => $options['click_action'] ?? url('/')],
                ])
            );

        if (count($tokens) === 1) {
            $this->messaging->send($message->toToken($tokens[0]));

            return [
                'success' => 1,
                'failure' => 0,
                'failed_tokens' => [],
                'tokens' => $tokens,
            ];
        }

        $report = $this->messaging->sendMulticast($message, $tokens);

        $failedTokens = [];
        foreach ($report->failures()->getItems() as $index => $failure) {
            $failedTokens[] = $tokens[$index];
        }

        return [
            'success'       => $report->successes()->count(),
            'failure'       => $report->failures()->count(),
            'failed_tokens' => $failedTokens,
            'tokens'        => $tokens,
        ];
    }
}
