<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Simulacao;

class DashboardController extends Controller
{
    public function index()
    {
        $kpis = [
            'total'        => Simulacao::count(),
            'pendentes'    => Simulacao::where('status_contato', 'pendente')->count(),
            'em_andamento' => Simulacao::where('status_contato', 'em andamento')->count(),
            'concluidos'   => Simulacao::where('status_contato', 'concluído')->count(),
        ];

        return view('admin.dashboard', compact('kpis'));
    }
}
