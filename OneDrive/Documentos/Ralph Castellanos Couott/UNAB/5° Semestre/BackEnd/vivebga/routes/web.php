<?php

use App\Http\Controllers\EventoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PromotorController;
use App\Http\Controllers\MainController;

Route::get('/', [HomeController::class, 'welcome'])->name('welcome');

Auth::routes();

// Si alguien entra a /home, lo redirigimos al inicio:
Route::get('/home', function () {
    return redirect()->route('main');
});

Route::middleware(['auth'])->group(function () {
    // Solo los organizadores pueden crear eventos
    Route::get('/crear-evento', [PromotorController::class, 'index'])->name('crear-evento');
    Route::post('/crear-evento', [PromotorController::class, 'store'])->name('crear-evento.store');

    // Vista de los eventos del organizador
    Route::get('/mis-eventos', [PromotorController::class, 'listaEventos'])->name('mis-eventos');
});

Route::post('/eventos', [EventoController::class, 'store'])
    ->middleware('auth')
    ->name('eventos.store');

Route::get('/eventos', [EventoController::class, 'index'])->name('eventos.index');

Route::get('/main', [MainController::class, 'index'])->name('main');

Route::get('/evento/{id}', [EventoController::class, 'show'])->name('evento.detalle');
