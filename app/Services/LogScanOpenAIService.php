<?php

namespace App\Services;

use App\Models\LogScan;
use OpenAI\Laravel\Facades\OpenAI;

class LogScanOpenAIService
{
    public function processLog(LogScan $logScan): void
    {
        $prompt = "";

        try {
            $result = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

            $responseContent = $result->choices[0]->message->content;

            $logScan->analysis_result = $responseContent;
            $logScan->save();

        } catch (\Exception $e) {
            \Log::error('Erro ao chamar a API da OpenAI: ' . $e->getMessage());
        }
    }
}
