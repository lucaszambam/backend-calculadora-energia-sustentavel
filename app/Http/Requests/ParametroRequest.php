<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParametroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_cidade' => ['nullable', 'integer', 'exists:cidades,id_cidade'],

            'uf'        => ['nullable', 'string', 'size:2', 'required_with:cidade'],
            'cidade'    => ['nullable', 'string', 'min:2', 'required_with:uf'],

            'incluir_eficiencias' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_cidade.exists'       => 'Cidade informada não existe.',
            'uf.size'                => 'UF deve ter exatamente 2 caracteres.',
            'uf.required_with'       => 'Informe a cidade ao fornecer a UF.',
            'cidade.required_with'   => 'Informe a UF ao fornecer a cidade.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'uf' => $this->filled('uf') ? mb_strtoupper(trim($this->uf)) : $this->uf,
            'cidade' => $this->filled('cidade') ? trim($this->cidade) : $this->cidade,
            'incluir_eficiencias' => filter_var($this->incluir_eficiencias, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false,
        ]);
    }

    public function passedValidation(): void
    {
        if (!$this->filled('id_cidade') && !($this->filled('uf') && $this->filled('cidade'))) {
            abort(response()->json([
                'message' => 'Informe "id_cidade" OU o par "uf" e "cidade".'
            ], 422));
        }
    }
}
