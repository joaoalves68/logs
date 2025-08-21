<?php

namespace App\Observers;

use App\Models\LogScanDetail;
use App\Jobs\ProcessWhoisApiRequest; // Importe o Job

class LogScanDetailObserver
{
    public function created(LogScanDetail $logScanDetail): void
    {
        if ($logScanDetail->domain !== null) {
            ProcessWhoisApiRequest::dispatch($logScanDetail);
        }
    }
}
