<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
                function ($attribute, $value, $fail) {
                    if (!str_ends_with($value->getClientOriginalName(), '.txt')) {
                        $fail('Apenas arquivos .txt são permitidos.');
                    }
                },
                'max:2048',
            ],
        ];
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
