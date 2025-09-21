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

        if ($request->filled('id_cidade')) {
            $param = Parametro::with($incluirEf ? ['eficiencias'] : [])
                ->where('id_cidade', $request->id_cidade)
                ->first();

            if ($param) {
                return response()->json($this->payloadCidade($param, $incluirEf));
            }

            $cidade = Cidade::with('estado')->find($request->id_cidade);
            if ($cidade) {
                return response()->json($this->payloadEstado($cidade->estado->id_estado, $incluirEf));
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
                    $param = Parametro::with($incluirEf ? ['eficiencias'] : [])
                        ->where('id_cidade', $cidade->id_cidade)
                        ->first();

                    if ($param) {
                        return response()->json($this->payloadCidade($param, $incluirEf));
                    }

                    return response()->json($this->payloadEstado($estado->id_estado, $incluirEf));
                }

                return response()->json($this->payloadEstado($estado->id_estado, $incluirEf));
            }

            return response()->json($this->payloadNacional());
        }

        return response()->json(['message' => 'Parâmetros insuficientes.'], 422);
    }

    private function payloadCidade(Parametro $p, bool $incluirEf): array
    {
        return [
            'fonte' => 'cidade',
            'id_parametro' => $p->id_parametro,
            'id_cidade' => $p->id_cidade,
            'tarifa_base' => (float) $p->tarifa_base,
            'taxa_distribuicao' => (float) $p->taxa_distribuicao,
            'co2_por_kwh' => (float) $p->co2_por_kwh,
            'eficiencias' => $incluirEf ? $p->eficiencias->pluck('eficiencia_valor', 'id_segmento')->map(fn($v)=>(float)$v) : (object)[],
            'atualizado_em' => optional($p->updated_at)->toDateTimeString(),
        ];
    }

    private function payloadEstado(int $idEstado, bool $incluirEf): array
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
                ->select('id_segmento', DB::raw('AVG(eficiencia_valor) AS val'))
                ->groupBy('id_segmento')
                ->pluck('val','id_segmento')
                ->map(fn($v)=>(float)$v)
                ->toArray();
        }

        return [
            'fonte' => 'estado',
            'id_parametro' => null,
            'id_cidade' => null,
            'tarifa_base' => (float) $m->tarifa_base,
            'taxa_distribuicao' => (float) $m->taxa_distribuicao,
            'co2_por_kwh' => (float) $m->co2_por_kwh,
            'eficiencias' => $incluirEf ? $ef : (object)[],
            'atualizado_em' => null,
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
