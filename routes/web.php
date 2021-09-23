<?php

use App\Http\Controllers\ClientesController;
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
    'cliente' => ClientesController::class
]);

Route::get('cliente/{id}/delete', [ClientesController::class, 'delete'])->name('cliente.delete');
Route::get('/clientes/search', [ClientesController::class, 'search'])->name('cliente.search');
require __DIR__.'/auth.php';

