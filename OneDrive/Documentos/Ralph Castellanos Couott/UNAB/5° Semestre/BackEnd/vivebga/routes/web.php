<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PromotorController;

Route::get('/', [HomeController::class, 'welcome'])->name('welcome');

Auth::routes();

// Si alguien entra a /home, lo redirigimos al inicio:
Route::get('/home', function () {
    return redirect()->route('welcome');


});

Route::middleware(['auth'])->group(function () {
    Route::get('/promotor', [PromotorController::class, 'index'])->name('promotor.index');
    Route::post('/promotor', [PromotorController::class, 'store'])->name('promotor.store');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/promotor/mis-eventos', [App\Http\Controllers\PromotorController::class, 'listaEventos'])
        ->name('promotor.mis-eventos');
});


Route::post('/eventos', [App\Http\Controllers\EventoController::class, 'store'])
    ->middleware('auth')
    ->name('eventos.store');