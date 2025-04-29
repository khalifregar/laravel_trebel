<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\OtpService;

class OtpServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(OtpService::class, function ($app) {
            return new OtpService();
        });
    }

    public function boot()
    {
        //
    }
}
