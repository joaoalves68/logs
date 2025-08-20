<?php

namespace App\Providers;

use App\Models\LogScan;
use App\Observers\LogScanObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        LogScan::observe(LogScanObserver::class);
    }
}
