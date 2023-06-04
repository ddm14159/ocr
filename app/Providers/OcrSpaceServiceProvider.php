<?php

namespace App\Providers;

use App\Services\OcrSpaceService;
use Illuminate\Support\ServiceProvider;

class OcrSpaceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(OcrSpaceService::class, function () {
            return new OcrSpaceService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
