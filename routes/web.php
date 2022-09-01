<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/ativos', [\App\Http\Controllers\AtivoController::class, 'index'])->name('ativos.index');
Route::get('/ativos/cadastrar', [\App\Http\Controllers\AtivoController::class, 'create'])->name('ativos.create');
Route::post('/ativos/cadastrar', [\App\Http\Controllers\AtivoController::class, 'store'])->name('ativos.store');
Route::get('/ativos/{ativo}', [\App\Http\Controllers\AtivoController::class, 'show'])->name('ativos.show');
Route::get('/ativos/pesquisar/autocomplete', [\App\Http\Controllers\AtivoController::class, 'autocomplete'])->name('ativos.autocomplete');

Route::get('/negociacoes', [\App\Http\Controllers\NegociacaoController::class, 'index'])->name('negociacoes.index');
Route::get('/negociacoes/cadastrar', [\App\Http\Controllers\NegociacaoController::class, 'create'])->name('negociacoes.create');
Route::post('/negociacoes/cadastrar', [\App\Http\Controllers\NegociacaoController::class, 'store'])->name('negociacoes.store');
Route::get('/negociacoes/{negociacao}', [\App\Http\Controllers\NegociacaoController::class, 'show'])->name('negociacoes.show');
Route::get('/negociacoes/{negociacao}/editar', [\App\Http\Controllers\NegociacaoController::class, 'edit'])->name('negociacoes.edit');
Route::put('/negociacoes/{negociacao}/editar', [\App\Http\Controllers\NegociacaoController::class, 'update'])->name('negociacoes.update');

Route::get('/apuracoes', [\App\Http\Controllers\ApuracaoController::class, 'index'])->name('apuracoes.index');
Route::get('/apuracoes/cadastrar', [\App\Http\Controllers\ApuracaoController::class, 'create'])->name('apuracoes.create');
Route::post('/apuracoes/cadastrar', [\App\Http\Controllers\ApuracaoController::class, 'store'])->name('apuracoes.store');
Route::get('/apuracoes/{apuracao}', [\App\Http\Controllers\ApuracaoController::class, 'show'])->name('apuracoes.show');
Route::delete('/apuracoes/{apuracao}', [\App\Http\Controllers\ApuracaoController::class, 'destroy'])->name('apuracoes.destroy');

// Route::get('/user', [UserController::class, 'index']);
