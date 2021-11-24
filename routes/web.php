<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SeguradorasController;
use App\Http\Controllers\FuncionariosController;
use App\Http\Controllers\SegurosController;
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

Route::get('/', [HomeController::class, 'home']);

Route::get('/dashboard', [DashboardController::class, 'dashboard'])
    ->name('dashboard');

Route::get('/areacliente', [ClienteController::class, 'areacliente'])
    ->name('areacliente');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::resources([
    'cliente' => ClientesController::class,
    'seguradora' => SeguradorasController::class,
    'funcionario' => FuncionariosController::class,
    'seguro' => SegurosController::class
]);

Route::get('cliente/{id}/delete', [ClientesController::class, 'delete'])->name('cliente.delete');
Route::get('/clientes/search', [ClientesController::class, 'search'])->name('cliente.search');

Route::get('seguradora/{id}/delete', [SeguradorasController::class, 'delete'])->name('seguradora.delete');
Route::get('seguradoras/search', [SeguradorasController::class, 'search'])->name('seguradora.search');

Route::get('funcionario/{id}/delete', [FuncionariosController::class, 'delete'])->name('funcionario.delete');
Route::get('funcionarios/search', [FuncionariosController::class, 'search'])->name('funcionario.search');

Route::get('seguro/{id}/delete', [SegurosController::class, 'delete'])->name('seguro.delete');
Route::get('seguros/search', [SegurosController::class, 'search'])->name('seguro.search');


Route::get('/carrinho/add/{id}', [SegurosController::class, 'adicionar_produto'])
    ->name('adicionar_produto');

Route::get('/carrinho/remove/{id}', [SegurosController::class, 'remover_produto'])
    ->name('remover_produto');

Route::get('/carrinho/encerrar', [SegurosController::class, 'encerrar_venda'])
    ->name('encerrar_venda');

require __DIR__.'/auth.php';

