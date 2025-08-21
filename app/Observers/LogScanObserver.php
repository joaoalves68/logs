<?php

namespace App\Observers;

use App\Models\LogScan;
use App\Services\LogFileProcessorService;
use App\Jobs\ProcessLogScanAnalysis;
use Illuminate\Support\Facades\Log;
use App\Jobs\ProcessWhoisApiRequest;
use Illuminate\Support\Facades\Bus;

class LogScanObserver
{
    public function created(LogScan $logScan): void
    {
        try {
            $fileProcessorService = new LogFileProcessorService();
            $fileProcessorService->processFile($logScan);

            $logScanDetails = $logScan->details;
            $whoisJobs = $logScanDetails->map(function ($detail) {
                return new ProcessWhoisApiRequest($detail);
            })->all();


            Bus::batch($whoisJobs)
                ->catch(function ($batch, $ex) {
                    Log::error('Um ou mais Jobs no batch falharam.', ['exception' => $ex->getMessage()]);
                })
                ->finally(function ($batch) use ($logScan) {
                    Log::info('Batch de Jobs da Whois API finalizado.');

                    ProcessLogScanAnalysis::dispatch($logScan);
                })
                ->dispatch();

        } catch (\Exception $e) {
            Log::error('Erro ao processar arquivo do LogScan ID ' . $logScan->id . ': ' . $e->getMessage());
        }
    }
}
