<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Api\SimulacaoApiController;
use App\Http\Controllers\Api\ParametroApiController;

use Illuminate\Http\Request;

use App\Models\Cidade;

Route::post('/simulacoes', [SimulacaoApiController::class, 'store']);

Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login.form');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');


Route::get('/parametros', [ParametroController::class, 'index']);

Route::get('/cidades', function (Request $request) {
    $estadoId = $request->query('estado_id');
    return Cidade::where('id_estado', $estadoId)
                 ->orderBy('nome')
                 ->get(['id_cidade','nome']);
});
