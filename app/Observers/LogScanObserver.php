<?php

namespace App\Observers;

use App\Models\LogScan;
use App\Services\LogFileProcessorService;
use App\Services\LogScanOpenAIService;
use Illuminate\Support\Facades\Log;

class LogScanObserver
{
    public function created(LogScan $logScan): void
    {
        try {
            $fileProcessorService = new LogFileProcessorService();
            $fileProcessorService->processFile($logScan);
        } catch (\Exception $e) {
            Log::error('Erro ao processar arquivo do LogScan ID ' . $logScan->id . ': ' . $e->getMessage());
        }

        try {
            $scanOpenAIService = new LogScanOpenAIService();
            $scanOpenAIService->processLog($logScan);
        } catch (\Exception $e) {
            Log::error('Erro ao processar detalhes do LogScan ID ' . $logScan->id . ': ' . $e->getMessage());
        }
    }
}
