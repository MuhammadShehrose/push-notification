<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Http\JsonResponse;
use App\Mail\PushNotificationMail;
use App\Services\Push\PushManager;
use Illuminate\Support\Facades\Mail;
use App\Services\Push\FcmPushService;
use App\Http\Requests\SendPushRequest;

class PushController extends Controller
{
    public function __construct(private PushManager $push) {}

    public function send(SendPushRequest $request): JsonResponse
    {
        $p = $request->payload();
        $report = null;
        try {
            if (config('app.enable_push_notification')) {
                $report = $this->push->send(
                    tokens: $p['tokens'],
                    title: $p['title'],
                    body: $p['body'],
                    data: $p['data'] ?? [],
                    options: [
                        'click_action' => $p['click_action'] ?? null,
                        'image'        => $p['image'] ?? null,
                    ],
                );
            }

            if (config('app.enable_email') && count($p['email']) > 0) {
                Mail::to($p['email'])->queue(new PushNotificationMail($p));
            }

            return response()->json([
                'ok'      => true,
                'message' => 'Push sent',
                'report'  => $report,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'ok'      => false,
                'message' => 'Push failed',
                'error'   => $e->getMessage(),
            ], 422);
        }
    }
}
