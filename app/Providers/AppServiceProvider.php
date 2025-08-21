<?php

namespace App\Providers;

use App\Models\LogScan;
use App\Observers\LogScanObserver;
use App\Models\LogScanDetail;
use App\Observers\LogScanDetailObserver;
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
        LogScanDetail::observe(LogScanDetailObserver::class);
    }
}
