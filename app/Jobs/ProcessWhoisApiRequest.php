<?php

namespace App\Jobs;

use App\Models\LogScanDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Batchable;

class ProcessWhoisApiRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    protected LogScanDetail $logScanDetail;

    public function __construct(LogScanDetail $logScanDetail)
    {
        $this->logScanDetail = $logScanDetail;
    }

    public function handle(): void
    {
        $whoisApiKey = config('services.whois.api_key');
        $whoisApiUrl = config('services.whois.url');

        if (empty($whoisApiKey) || empty($whoisApiUrl)) {
            Log::warning('Configurações de API Whois incompletas no arquivo .env. Requisição cancelada.');
            return;
        }

        try {
            $domain = $this->logScanDetail->domain;

            $response = Http::post($whoisApiUrl, [
                'domainName' => $domain,
                'apiKey' => $whoisApiKey,
                'outputFormat' => 'JSON'
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['WhoisRecord'])) {
                    $whoisRecord = $data['WhoisRecord'];

                    $simplifiedData = [
                        'whois' => [
                            'domain_age_days' => $whoisRecord['estimatedDomainAge'] ?? null,
                            'creation_date' => $whoisRecord['createdDate'] ?? null,
                            'expiration_date' => $whoisRecord['expiresDate'] ?? null,
                            'status' => $whoisRecord['status'] ?? null,
                            'name_servers' => $whoisRecord['nameServers']['hostNames'] ?? [],
                            'registrant' => $whoisRecord['registrant'] ?? null
                        ]
                    ];

                    $this->logScanDetail->extra = (object) $simplifiedData;
                    $this->logScanDetail->save();
                    Log::info('Dados Whois recebidos para o domínio ' . $domain . ':', (array) $simplifiedData);
                } else {
                    Log::error('Falha na requisição Whois para ' . $domain . '. Status: ' . $response->status());
                }
            }

        } catch (\Exception $e) {
            Log::error('Erro ao conectar à API Whois para ' . $domain . ': ' . $e->getMessage());
        }
    }
}
