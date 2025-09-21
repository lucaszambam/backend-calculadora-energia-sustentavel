<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Api\SimulacaoApiController;
use App\Http\Controllers\Api\ParametroApiController;

use Illuminate\Http\Request;

use App\Models\Cidade;

Route::post('/simulacoes', [SimulacaoApiController::class, 'store']);
Route::get('/parametros', [ParametroApiController::class, 'index']);

Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login.form');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

Route::get('/cidades', function (Request $request) {
    $estadoId = $request->query('estado_id', false);
    $estadoSigla = $request->query('estado_sigla', false);
    if ($estadoId) {
        return Cidade::where('id_estado', $estadoId)
                     ->orderBy('nome')
                     ->get(['id_cidade','nome']);
    } else if ($estadoSigla) {
        return Cidade::whereHas('estado', function ($q) use ($estadoSigla) {
            $q->where('sigla', $estadoSigla);
        })->orderBy('nome')->get(['id_cidade','nome']);
    }
});


