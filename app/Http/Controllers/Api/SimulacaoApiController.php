<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSimulacaoRequest;
use App\Models\Parametro;
use App\Models\Simulacao;

class SimulacaoApiController extends Controller
{
    public function store(StoreSimulacaoRequest $request)
    {

        $ok = true;
        if ($request->id_parametro) {
            $ok = Parametro::where('id_parametro', $request->id_parametro)
                        ->where('id_cidade', $request->id_cidade)
                        ->exists();
        }
        if (!$ok) {
            return response()->json(['message' => 'Parâmetro não pertence à cidade informada.'], 422);
        }

        $sim = Simulacao::create($request->only([
            'id_cidade','id_tipo_energia','id_tipo_instalacao','id_segmento','id_parametro',
            'valor_conta_medio','consumo_kwh_estimado','economia_reais','economia_percentual',
            'co2_evitado','nome_contato','email_contato','telefone_contato'
        ]));

        return response()->json(['id_simulacao' => $sim->id_simulacao], 201);
    }
}
