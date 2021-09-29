<?php

use App\Http\Controllers\ClientesController;
use App\Http\Controllers\SeguradorasController;
use App\Http\Controllers\FuncionariosController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::resources([
    'cliente' => ClientesController::class,
    'seguradora' => SeguradorasController::class,
    'funcionario' => FuncionariosController::class
]);

Route::get('cliente/{id}/delete', [ClientesController::class, 'delete'])->name('cliente.delete');
Route::get('/clientes/search', [ClientesController::class, 'search'])->name('cliente.search');

Route::get('seguradora/{id}/delete', [SeguradorasController::class, 'delete'])->name('seguradora.delete');
Route::get('seguradoras/search', [SeguradorasController::class, 'search'])->name('seguradora.search');

Route::get('funcionario/{id}/delete', [FuncionariosController::class, 'delete'])->name('funcionario.delete');
Route::get('funcionarios/search', [FuncionariosController::class, 'search'])->name('funcionario.search');
require __DIR__.'/auth.php';

