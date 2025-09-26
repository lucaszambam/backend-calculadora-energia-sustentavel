<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ParametroRequest;
use App\Models\Cidade;
use App\Models\Estado;
use App\Models\Parametro;
use App\Models\Eficiencia;
use Illuminate\Support\Facades\DB;

class ParametroApiController extends Controller
{
    public function index(ParametroRequest $request)
    {
        $incluirEf = (bool) $request->boolean('incluir_eficiencias', false);

        $seg = $request->integer('ef_segmento');
        $te  = $request->integer('ef_tipo_energia');
        $ti  = $request->integer('ef_tipo_instalacao');

        if ($request->filled('id_cidade')) {
            $param = Parametro::when($incluirEf, function($q) use ($seg,$te,$ti) {
                    $q->with(['eficiencias' => function($qq) use ($seg,$te,$ti) {
                        if ($seg) $qq->where('id_segmento', $seg);
                        if ($te)  $qq->where('id_tipo_energia', $te);
                        if ($ti)  $qq->where('id_tipo_instalacao', $ti);
                    }]);
                })
                ->where('id_cidade', $request->id_cidade)
                ->first();

            if ($param) {
                return response()->json($this->payloadCidade($param, $incluirEf));
            }

            $cidade = Cidade::with('estado')->find($request->id_cidade);
            if ($cidade) {
                return response()->json($this->payloadEstado($cidade->estado->id_estado, $incluirEf, $seg,$te,$ti));
            }

            return response()->json($this->payloadNacional());
        }

        if ($request->filled('uf') && $request->filled('cidade')) {
            $estado = Estado::where('sigla', $request->uf)->first();
            if ($estado) {
                $cidade = Cidade::where('id_estado', $estado->id_estado)
                            ->where(DB::raw('unaccent(lower(nome))'), unaccent(mb_strtolower($request->cidade)))
                            ->first();

                if ($cidade) {
                    $param = Parametro::when($incluirEf, function($q) use ($seg,$te,$ti) {
                            $q->with(['eficiencias' => function($qq) use ($seg,$te,$ti) {
                                if ($seg) $qq->where('id_segmento', $seg);
                                if ($te)  $qq->where('id_tipo_energia', $te);
                                if ($ti)  $qq->where('id_tipo_instalacao', $ti);
                            }]);
                        })
                        ->where('id_cidade', $cidade->id_cidade)
                        ->first();

                    if ($param) {
                        return response()->json($this->payloadCidade($param, $incluirEf));
                    }

                    return response()->json($this->payloadEstado($estado->id_estado, $incluirEf, $seg,$te,$ti));
                }

                return response()->json($this->payloadEstado($estado->id_estado, $incluirEf, $seg,$te,$ti));
            }

            return response()->json($this->payloadNacional());
        }

        return response()->json(['message' => 'Parâmetros insuficientes.'], 422);
    }

    private function payloadCidade(Parametro $p, bool $incluirEf): array
    {
        return [
            'fonte' => 'cidade',
            'id_parametro'      => $p->id_parametro,
            'id_cidade'         => $p->id_cidade,
            'tarifa_base'       => (float) $p->tarifa_base,
            'taxa_distribuicao' => (float) $p->taxa_distribuicao,
            'co2_por_kwh'       => (float) $p->co2_por_kwh,
            'eficiencias'       => $incluirEf
                ? $p->eficiencias->map(fn($ef) => [
                    'id_segmento'        => $ef->id_segmento,
                    'id_tipo_energia'    => $ef->id_tipo_energia,
                    'id_tipo_instalacao' => $ef->id_tipo_instalacao,
                    'eficiencia_valor'   => (float) $ef->eficiencia_valor
                ])->values()
                : [],
            'atualizado_em' => optional($p->updated_at)->toDateTimeString(),
        ];
    }


    private function payloadEstado(int $idEstado, bool $incluirEf, ?int $seg=null, ?int $te=null, ?int $ti=null): array
    {
        $cidadeIds = Cidade::where('id_estado', $idEstado)->pluck('id_cidade');

        $m = Parametro::whereIn('id_cidade', $cidadeIds)
            ->selectRaw('AVG(tarifa_base) AS tarifa_base, AVG(taxa_distribuicao) AS taxa_distribuicao, AVG(co2_por_kwh) AS co2_por_kwh')
            ->first();

        if (!$m || is_null($m->tarifa_base)) {
            return $this->payloadNacional();
        }

        $ef = [];
        if ($incluirEf) {
            $ef = Eficiencia::whereIn('id_parametro', Parametro::whereIn('id_cidade', $cidadeIds)->pluck('id_parametro'))
                ->when($seg, fn($q)=>$q->where('id_segmento', $seg))
                ->when($te,  fn($q)=>$q->where('id_tipo_energia', $te))
                ->when($ti,  fn($q)=>$q->where('id_tipo_instalacao', $ti))
                ->get(['id_segmento','id_tipo_energia','id_tipo_instalacao','eficiencia_valor'])
                ->map(fn($e)=>[
                    'id_segmento'        => (int) $e->id_segmento,
                    'id_tipo_energia'    => (int) $e->id_tipo_energia,
                    'id_tipo_instalacao' => (int) $e->id_tipo_instalacao,
                    'eficiencia_valor'   => (float) $e->eficiencia_valor,
                ])->values();
        }

        return [
            'fonte' => 'estado',
            'id_parametro'      => null,
            'id_cidade'         => null,
            'tarifa_base'       => (float) $m->tarifa_base,
            'taxa_distribuicao' => (float) $m->taxa_distribuicao,
            'co2_por_kwh'       => (float) $m->co2_por_kwh,
            'eficiencias'       => $incluirEf ? $ef : [],
            'atualizado_em'     => null,
        ];
    }

    private function payloadNacional(): array
    {
        $tarifa = (float) env('PARAM_NACIONAL_TARIFA_BASE', 0.75);
        $taxa   = (float) env('PARAM_NACIONAL_TAXA_DISTRIBUICAO', 0.18);
        $co2    = (float) env('PARAM_NACIONAL_CO2_KWH', 0.084);

        $ef = [
            1 => (float) env('PARAM_NACIONAL_EF_RESID', 0.30),
            2 => (float) env('PARAM_NACIONAL_EF_COM', 0.28),
            3 => (float) env('PARAM_NACIONAL_EF_IND', 0.26),
            4 => (float) env('PARAM_NACIONAL_EF_RUR', 0.32),
        ];

        return [
            'fonte' => 'nacional',
            'id_parametro' => null,
            'id_cidade' => null,
            'tarifa_base' => $tarifa,
            'taxa_distribuicao' => $taxa,
            'co2_por_kwh' => $co2,
            'eficiencias' => $ef,
            'atualizado_em' => null,
        ];
    }
}
