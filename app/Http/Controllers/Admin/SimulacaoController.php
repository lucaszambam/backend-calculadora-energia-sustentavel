<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Simulacao;
use Illuminate\Http\Request;

class SimulacaoController extends Controller
{
    public function index(Request $req)
    {
        $q = Simulacao::query()->with(['cidade.estado','segmento','tipoEnergia','tipoInstalacao'])
              ->orderByDesc('data_hora');

        if ($req->filled('data_ini'))  $q->whereDate('data_hora', '>=', $req->date('data_ini'));
        if ($req->filled('data_fim'))  $q->whereDate('data_hora', '<=', $req->date('data_fim'));
        if ($req->filled('status'))    $q->where('status_contato', $req->status);
        if ($req->filled('uf'))        $q->whereHas('cidade.estado', fn($qq) => $qq->where('sigla', $req->uf));
        if ($req->filled('cidade'))    $q->whereHas('cidade', fn($qq) => $qq->where('nome', $req->cidade));
        if ($req->filled('segmento'))  $q->whereHas('segmento', fn($qq) => $qq->where('nome', $req->segmento));
        if ($req->filled('tipo_energia')) {
            $q->whereHas('tipoEnergia', fn($qq) => $qq->where('descricao', $req->tipo_energia));
        }

        $simulacoes = $q->paginate(20)->withQueryString();

        return view('admin.simulacoes.index', compact('simulacoes'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $data = $request->validate([
            'status' => 'required|string|in:pendente,em andamento,concluído'
        ]);

        $sim = Simulacao::findOrFail($id);
        $sim->status_contato = $data['status'];
        $sim->save();

        return back()->with('ok', 'Status atualizado com sucesso.');
    }

    public function show(int $id)
    {
        $simulacao = Simulacao::with([
            'cidade.estado',
            'segmento',
            'tipoEnergia',
            'tipoInstalacao',
            'parametro'
        ])->findOrFail($id);

        return view('admin.simulacoes.show', compact('simulacao'));
    }

}
