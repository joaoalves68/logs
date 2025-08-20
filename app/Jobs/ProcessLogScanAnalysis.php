<?php

namespace App\Jobs;

use App\Models\LogScan;
use App\Services\LogScanOpenAIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessLogScanAnalysis implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected LogScan $logScan;

    public function __construct(LogScan $logScan)
    {
        $this->logScan = $logScan;
    }

    public function handle(LogScanOpenAIService $openAIService): void
    {
        try {
            $openAIService->processLog($this->logScan);
        } catch (\Exception $e) {
            Log::error('Erro ao executar o Job ProcessLogScanAnalysis para LogScan ID ' . $this->logScan->id . ': ' . $e->getMessage());
        }
    }
}
