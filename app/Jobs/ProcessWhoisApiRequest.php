<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProcessWhoisApiRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $domain;

    public function __construct(string $domain)
    {
        $this->domain = $domain;
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
            $response = Http::post($whoisApiUrl, [
                'domainName' => $this->domain,
                'apiKey' => $whoisApiKey,
                'outputFormat' => 'JSON'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Dados Whois recebidos para o domínio ' . $this->domain . ':', $data);
            } else {
                Log::error('Falha na requisição Whois para ' . $this->domain . '. Status: ' . $response->status() . ' Resposta: ' . $response->body());
            }

        } catch (\Exception $e) {
            Log::error('Erro ao conectar à API Whois para ' . $this->domain . ': ' . $e->getMessage());
        }
    }
}
