<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Factory;

class PushTestNotification extends Command
{
    protected $signature = 'push:send {token}';
    protected $description = 'Send a test push notification to a device token';

    public function handle()
    {
        $token = $this->argument('token');

        $factory = (new Factory)->withServiceAccount(config('services.firebase.credentials'));
        $messaging = $factory->createMessaging();

        $message = [
            'token' => $token,
            'notification' => [
                'title' => 'Hello from Laravel 🚀',
                'body' => 'Your push notification is working!',
            ],
            'data' => [
                'click_action' => 'https://pushkit.test', // optional
            ]
        ];

        try {
            $messaging->send($message);
            $this->info("✅ Push sent to: {$token}");
        } catch (\Throwable $e) {
            $this->error("❌ Failed: " . $e->getMessage());
        }
    }
}
