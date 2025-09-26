<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cidade;
use App\Models\Estado;
use App\Models\Parametro;
use App\Models\Segmento;
use App\Models\Eficiencia;
use App\Models\TipoEnergia;
use App\Models\TipoInstalacao;
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
        $estados      = Estado::orderBy('sigla')->get(['id_estado','sigla','nome']);
        $segmentos    = Segmento::orderBy('id_segmento')->get();
        $energias     = TipoEnergia::orderBy('id_tipo_energia')->get();      
        $instalacoes  = TipoInstalacao::orderBy('id_tipo_instalacao')->get();

        return view('admin.parametros.create', compact('estados','segmentos','energias','instalacoes'));
    }

    public function store(Request $request)
    {
        $param = Parametro::create($request->only([
            'id_cidade','tarifa_base','taxa_distribuicao','co2_por_kwh'
        ]));

        // Percorre a matriz de eficiências
        foreach ($request->eficiencias as $id_segmento => $energias) {
            foreach ($energias as $id_tipo_energia => $instalacoes) {
                foreach ($instalacoes as $id_tipo_instalacao => $valor) {
                    Eficiencia::create([
                        'id_parametro'      => $param->id_parametro,
                        'id_segmento'       => $id_segmento,
                        'id_tipo_energia'   => $id_tipo_energia,
                        'id_tipo_instalacao'=> $id_tipo_instalacao,
                        'eficiencia_valor'  => $valor,
                    ]);
                }
            }
        }

        return redirect()->route('admin.parametros.index')
                        ->with('ok','Parâmetro cadastrado com sucesso!');
    }

    public function destroy($id)
    {
        $parametro = \App\Models\Parametro::findOrFail($id);
        $parametro->delete();

        return redirect()->route('admin.parametros.index')->with('ok', 'Parâmetro excluído com sucesso!');
    }

    public function edit(int $id)
    {
        $param       = Parametro::with(['cidade.estado','eficiencias'])->findOrFail($id);
        $segmentos   = Segmento::orderBy('id_segmento')->get();
        $energias    = TipoEnergia::orderBy('id_tipo_energia')->get();
        $instalacoes = TipoInstalacao::orderBy('id_tipo_instalacao')->get();

        $ef = [];
        foreach ($param->eficiencias as $e) {
            $ef[$e->id_segmento][$e->id_tipo_energia][$e->id_tipo_instalacao] = $e->eficiencia_valor;
        }

        return view('admin.parametros.edit', compact('param','segmentos','energias','instalacoes','ef'));
    }

    public function update(Request $request, int $id)
    {
        $param = Parametro::findOrFail($id);

        $param->update($request->only([
            'tarifa_base','taxa_distribuicao','co2_por_kwh'
        ]));

        Eficiencia::where('id_parametro',$param->id_parametro)->delete();

        foreach ($request->eficiencias as $id_segmento => $energias) {
            foreach ($energias as $id_tipo_energia => $instalacoes) {
                foreach ($instalacoes as $id_tipo_instalacao => $valor) {
                    Eficiencia::create([
                        'id_parametro'      => $param->id_parametro,
                        'id_segmento'       => $id_segmento,
                        'id_tipo_energia'   => $id_tipo_energia,
                        'id_tipo_instalacao'=> $id_tipo_instalacao,
                        'eficiencia_valor'  => $valor,
                    ]);
                }
            }
        }

        return redirect()->route('admin.parametros.index')
                        ->with('ok','Parâmetro atualizado com sucesso!');
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
