<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cidade;
use App\Models\Estado;
use App\Models\Parametro;
use App\Models\Segmento;
use App\Models\Eficiencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParametroController extends Controller
{
    public function index(Request $req)
    {
        // Lista com busca por UF/cidade
        $params = Parametro::with(['cidade.estado'])
            ->when($req->filled('uf'), fn($q) =>
                $q->whereHas('cidade.estado', fn($qq) => $qq->where('sigla', $req->uf))
            )
            ->when($req->filled('cidade'), fn($q) =>
                $q->whereHas('cidade', fn($qq) => $qq->where('nome', 'ilike', '%'.$req->cidade.'%'))
            )
            ->orderBy('id_parametro')
            ->paginate(20)->withQueryString();

        return view('admin.parametros.index', compact('params'));
    }

    public function create()
    {
        $estados   = Estado::orderBy('nome')->get(['id_estado','nome','sigla']);
        $segmentos = Segmento::orderBy('nome')->get(['id_segmento','nome']);
        return view('admin.parametros.create', compact('estados','segmentos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_cidade'         => 'required|exists:cidades,id_cidade',
            'tarifa_base'       => 'required|numeric|min:0',
            'taxa_distribuicao' => 'required|numeric|min:0',
            'co2_por_kwh'       => 'required|numeric|min:0',
            'eficiencias'       => 'required|array',
            'eficiencias.*.id_segmento' => 'required|exists:segmentos,id_segmento',
            'eficiencias.*.valor'       => 'required|numeric|min:0|max:1',
        ]);

        $param = Parametro::create([
            'id_cidade'        => $request->id_cidade,
            'tarifa_base'      => $request->tarifa_base,
            'taxa_distribuicao'=> $request->taxa_distribuicao,
            'co2_por_kwh'      => $request->co2_por_kwh,
        ]);

        foreach ($request->eficiencias as $ef) {
            Eficiencia::create([
                'id_parametro'  => $param->id_parametro,
                'id_segmento'   => $ef['id_segmento'],
                'eficiencia_valor' => $ef['valor'],
            ]);
        }

        return redirect()->route('admin.parametros.index')->with('ok', 'Parâmetro e eficiências cadastrados com sucesso!');
    }

    public function destroy($id)
    {
        $parametro = \App\Models\Parametro::findOrFail($id);
        $parametro->delete();

        return redirect()->route('admin.parametros.index')->with('ok', 'Parâmetro excluído com sucesso!');
    }

    public function edit(int $id)
    {
        $param = Parametro::with(['cidade.estado','eficiencias.segmento'])->findOrFail($id);
        $segmentos = Segmento::orderBy('id_segmento')->get();

        // Normaliza eficiências em array [id_segmento => valor]
        $ef = $param->eficiencias->keyBy('id_segmento')->map->eficiencia_valor->toArray();

        return view('admin.parametros.edit', compact('param','segmentos','ef'));
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'tarifa_base'       => 'required|numeric|min:0',
            'taxa_distribuicao' => 'required|numeric|min:0',
            'co2_por_kwh'       => 'required|numeric|min:0',
        ]);

        $param = Parametro::findOrFail($id);
        $param->update($data);

        return back()->with('ok', 'Parâmetros atualizados.');
    }

    public function updateEficiencias(Request $request, int $id)
    {
        $payload = $request->validate([
            'eficiencias'                 => 'required|array|min:1',
            'eficiencias.*.id_segmento'   => 'required|integer|exists:segmentos,id_segmento',
            'eficiencias.*.valor'         => 'required|numeric|min:0|max:1',
        ]);

        Parametro::findOrFail($id);

        $rows = [];
        foreach ($payload['eficiencias'] as $item) {
            $rows[] = [
                'id_parametro'     => $id,
                'id_segmento'      => $item['id_segmento'],
                'eficiencia_valor' => $item['valor'],
                'created_at'       => now(),
                'updated_at'       => now(),
            ];
        }

        DB::table('eficiencias')->upsert(
            $rows,
            ['id_parametro','id_segmento'],
            ['eficiencia_valor','updated_at']
        );

        return back()->with('ok', 'Eficiências atualizadas.');
    }
}
