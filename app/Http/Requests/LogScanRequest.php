<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class LogScanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'file' => [
                'required',
                'file',
                'max:2048',
                function ($attribute, $value, $fail) {
                    if (!str_ends_with($value->getClientOriginalName(), '.txt')) {
                        $fail('Apenas arquivos .txt são permitidos.');
                    }
                },
                function ($attribute, $value, $fail) {
                    $this->validateFileHeader($value, $fail);
                },
            ],
        ];
    }

    private function validateFileHeader(UploadedFile $file, \Closure $fail): void
    {
        if (!$file->isValid() || !file_exists($file->getRealPath())) {
            $fail('O arquivo enviado é inválido ou não pôde ser acessado.');
            return;
        }

        try {
            $firstLine = file_get_contents($file->getRealPath(), false, null, 0, 200);
            $firstLine = explode(PHP_EOL, $firstLine)[0] ?? '';
            $firstLine = trim(strtolower($firstLine));

        } catch (\Exception $e) {
            Log::error('Erro ao ler cabeçalho do arquivo para validação: ' . $e->getMessage());
            $fail('Não foi possível ler o cabeçalho do arquivo para validação.');
            return;
        }

        $expectedHeader = 'timestamp,domain,client_ip';

        if ($firstLine !== $expectedHeader) {
            $fail("O arquivo .txt deve ter o cabeçalho '{$expectedHeader}' na primeira linha.");
        }
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'O campo nome deve ser um texto.',
            'name.max' => 'O campo nome não pode ter mais de 255 caracteres.',

            'file.required' => 'O campo arquivo é obrigatório.',
            'file.file' => 'O campo arquivo deve ser um arquivo.',
            'file.mimes' => 'Apenas arquivos .txt são permitidos.',
            'file.max' => 'O arquivo não pode ser maior que 2 MB.',
        ];
    }
}
