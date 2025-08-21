<?php

namespace App\Services;

use App\Models\LogScan;
use App\Models\LogScanDetail;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;

class LogScanOpenAIService
{
    public function processLog(LogScan $logScan): void
    {
        $details = $logScan->details;

        if ($details->isEmpty()) {
            Log::info('LogScan ID ' . $logScan->id . ' não possui detalhes para análise da OpenAI.');
            return;
        }

        $logDataForPrompt = $details->map(function ($detail) {
            return [
                'id' => $detail->id,
                'timestamp' => $detail->timestamp,
                'domain' => $detail->domain,
                'client_ip' => $detail->client_ip,
                'whois_data' => $detail->extra ?? [],
            ];
        })->toJson(JSON_PRETTY_PRINT);

        $prompt = "Você é um analista de segurança. Sua tarefa é analisar a lista de registros de conexão abaixo.
        Cada registro contém:
        - domínio acessado
        - IP do cliente
        - informações de WHOIS/RDAP (em JSON dentro de 'whois_data')

        Para cada registro, classifique a requisição com:
        1 = MALICIOSA
        2 = MODERADA
        3 = SEGURA

        Use como critérios:
        - Reputação do domínio (se é conhecido, muito antigo ou suspeito)
        - Reputação do IP do cliente (se tiver conhecimento)
        - Dados de WHOIS/RDAP: idade do domínio, registrante, país, status (ex: clientTransferProhibited é comum em domínios legítimos), TLDs suspeitos (.ru, .xyz), uso de registrantes anônimos, etc.

        Retorne sua análise em JSON, mantendo a estrutura:
        [
        {
            \"id\": \"<ID do detalhe>\",
            \"classification\": <1|2|3>,
            \"analysis_reason\": \"<breve razão baseada no domínio/IP/whois>\"
        }
        ]

        Dados para análise:\n" . $logDataForPrompt;

        Log::debug('Prompt enviado para OpenAI para LogScan ID ' . $logScan->id . ":\n" . $prompt);

        try {
            $result = OpenAI::chat()->create([
                'model' => 'gpt-4-turbo',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
                'response_format' => ['type' => 'json_object'],
            ]);

            $responseContent = $result->choices[0]->message->content;
            Log::debug('Resposta bruta da OpenAI para LogScan ID ' . $logScan->id . ":\n" . $responseContent);

            $analysisResults = json_decode($responseContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Erro ao decodificar JSON da resposta da OpenAI: ' . json_last_error_msg());
            }

            foreach ($analysisResults as $analysis) {
                $logScanDetail = LogScanDetail::find($analysis['id']);
                if ($logScanDetail) {
                    $logScanDetail->classification = $analysis['classification'] ?? null;
                    $logScanDetail->analysis_reason = $analysis['analysis_reason'] ?? null;
                    $logScanDetail->save();
                } else {
                    Log::warning('Detalhe do LogScan ID ' . $analysis['id'] . ' não encontrado para atualização de análise.');
                }
            }

            Log::info('Análise da OpenAI concluída e detalhes atualizados para LogScan ID ' . $logScan->id);

        } catch (\Exception $e) {
            Log::error('Erro ao chamar a API da OpenAI para LogScan ID ' . $logScan->id . ': ' . $e->getMessage());
        }
    }
}
