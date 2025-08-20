<?php

namespace App\Observers;

use App\Models\LogScan;
use App\Services\LogFileProcessorService;
use App\Jobs\ProcessLogScanAnalysis;
use Illuminate\Support\Facades\Log;

class LogScanObserver
{
    public function created(LogScan $logScan): void
    {
        try {
            $fileProcessorService = new LogFileProcessorService();
            $fileProcessorService->processFile($logScan);
            Log::info('Arquivo do LogScan ID ' . $logScan->id . ' processado com sucesso. Despachando Job para OpenAI.');

            ProcessLogScanAnalysis::dispatch($logScan);

        } catch (\Exception $e) {
            Log::error('Erro ao processar arquivo do LogScan ID ' . $logScan->id . ': ' . $e->getMessage());
        }
    }
}
