<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreSimulacaoRequest extends FormRequest {
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'id_cidade' => 'required|integer|exists:cidades,id_cidade',
            'id_tipo_energia' => 'required|integer',
            'id_tipo_instalacao' => 'required|integer',
            'id_segmento' => 'required|integer',
            'id_parametro' => 'nullable|integer|exists:parametros,id_parametro',
            'valor_conta_medio' => 'required|numeric',
            'consumo_kwh_estimado' => 'required|numeric',
            'economia_reais' => 'required|numeric',
            'economia_percentual' => 'required|numeric',
            'co2_evitado' => 'required|numeric',
            'nome_contato' => 'nullable|string|max:255',
            'email_contato' => 'nullable|email|max:255',
            'telefone_contato' => 'nullable|string|max:30',
            'consentimento' => 'required|boolean'
        ];
    }

    public function messages(): array {
        return [
            'consentimento.in' => 'É necessário consentimento para salvar os dados (LGPD).',
            'email_contato.required_without' => 'Informe e-mail ou telefone.',
            'telefone_contato.required_without' => 'Informe e-mail ou telefone.',
        ];
    }
}
