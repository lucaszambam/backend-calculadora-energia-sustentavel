<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreSimulacaoRequest extends FormRequest {
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'consentimento' => 'required|boolean|in:1,true',
            'id_cidade' => 'required|integer|exists:cidades,id_cidade',
            'id_tipo_energia' => 'required|integer|exists:tipos_energia,id_tipo_energia',
            'id_tipo_instalacao' => 'required|integer|exists:tipos_instalacao,id_tipo_instalacao',
            'id_segmento' => 'required|integer|exists:segmentos,id_segmento',
            'id_parametro' => 'required|integer|exists:parametros,id_parametro',

            'valor_conta_medio' => 'required|numeric|gt:0',
            'consumo_kwh_estimado' => 'required|numeric|min:0',
            'economia_reais' => 'required|numeric|min:0',
            'economia_percentual' => 'required|numeric|min:0|max:100',
            'co2_evitado' => 'required|numeric|min:0',

            'nome_contato' => 'required|string|min:2',
            'email_contato' => 'nullable|email|required_without:telefone_contato',
            'telefone_contato' => 'nullable|string|required_without:email_contato',
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
