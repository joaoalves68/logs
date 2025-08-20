<?php

namespace App\Services;

use App\Models\LogScan;
use App\Models\LogScanDetail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class LogFileProcessorService
{
    public function processFile(LogScan $logScan): void
    {
        if (empty($logScan->path)) {
            Log::warning('LogScan sem caminho de arquivo para processamento: ID ' . $logScan->id);
            return;
        }

        try {
            $contents = Storage::disk('public')->get($logScan->path);
        } catch (\Exception $e) {
            Log::error('Não foi possível ler o arquivo do LogScan ID ' . $logScan->id . ': ' . $e->getMessage());
            return;
        }

        $lines = explode(PHP_EOL, $contents);

        foreach ($lines as $line) {
            $line = trim($line);

            if (empty($line) || strtolower($line) === 'timestamp,domain,client_ip') {
                continue;
            }

            $data = str_getcsv($line);

            if (count($data) === 3) {
                try {
                    LogScanDetail::create([
                        'log_scan_id' => $logScan->id,
                        'timestamp' => $data[0],
                        'domain' => $data[1],
                        'client_ip' => $data[2],
                    ]);
                } catch (\Exception $e) {
                    Log::error('Erro ao salvar detalhe do LogScan ID ' . $logScan->id . ': ' . $e->getMessage() . ' - Linha: ' . $line);
                }
            } else {
                Log::warning('Formato de linha inválido no arquivo do LogScan ID ' . $logScan->id . ': ' . $line);
            }
        }
        Log::info('Arquivo do LogScan ID ' . $logScan->id . ' processado com sucesso.');
    }
}
