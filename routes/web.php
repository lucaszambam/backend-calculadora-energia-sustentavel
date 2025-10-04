<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AuthController as AdminAuth; 
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SimulacaoController;
use App\Http\Controllers\Admin\ParametroController;
use App\Http\Controllers\Admin\AdminController;

Route::get('/admin/login', [AdminAuth::class, 'showLoginForm'])->name('admin.login.form');
Route::post('/admin/login', [AdminAuth::class, 'login'])->name('admin.login');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('administradores', AdminController::class)->except(['show', 'edit', 'update']);

    Route::get('/simulacoes', [SimulacaoController::class, 'index'])->name('simulacoes.index');
    Route::patch('/simulacoes/{id}/status', [SimulacaoController::class, 'updateStatus'])->name('simulacoes.updateStatus');
    Route::get('/simulacoes/{id}', [SimulacaoController::class, 'show'])->name('simulacoes.show');

    Route::get('/parametros', [ParametroController::class, 'index'])->name('parametros.index');
    Route::get('/parametros/create', [ParametroController::class, 'create'])->name('parametros.create');
    Route::post('/parametros', [ParametroController::class, 'store'])->name('parametros.store');
    Route::delete('/parametros/{id}', [ParametroController::class, 'destroy'])->name('parametros.destroy');
    Route::get('/parametros/{id}/edit', [ParametroController::class, 'edit'])->name('parametros.edit');
    Route::put('/parametros/{id}', [ParametroController::class, 'update'])->name('parametros.update');
    Route::put('/parametros/{id}/eficiencias', [ParametroController::class, 'updateEficiencias'])->name('parametros.updateEficiencias');
});


Route::get('/', function () {
    return redirect()->route('admin.login.form');
});
