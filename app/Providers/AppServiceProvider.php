<?php

namespace App\Providers;

use App\Services\Push\PushManager;
use App\Services\Push\FcmPushService;
use Illuminate\Support\ServiceProvider;
use App\Services\Push\OneSignalPushService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(FcmPushService::class, fn () => FcmPushService::makeFromConfig());
        $this->app->singleton(OneSignalPushService::class, fn () => new OneSignalPushService());
        $this->app->singleton(PushManager::class, fn ($app) => new PushManager(
            $app->make(FcmPushService::class),
            $app->make(OneSignalPushService::class),
        ));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
