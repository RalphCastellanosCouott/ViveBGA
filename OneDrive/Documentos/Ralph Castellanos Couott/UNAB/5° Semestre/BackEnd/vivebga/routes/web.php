<?php

use App\Http\Controllers\EventoController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes();

// Página principal
Route::get('/', [HomeController::class, 'index'])->name('main');

// Redirección /home → /
Route::get('/home', fn() => redirect()->route('main'));

// Rutas públicas
Route::get('/eventos', [EventoController::class, 'index'])->name('events.index');
Route::get('/evento/{id}', [EventoController::class, 'show'])->name('events.detail');
Route::get('/organizer/{id}', [EventoController::class, 'perfilOrganizador'])->name('organizer.profile');

// --------------------------
// Rutas protegidas (solo usuarios logueados)
// --------------------------
Route::middleware(['auth'])->group(function () {

    // --------------------------
    // Rutas organizador
    // --------------------------
    Route::get('/eventos/crear', [EventoController::class, 'create'])->name('events.create');
    Route::post('/eventos', [EventoController::class, 'store'])->name('events.store');

    // --------------------------
    // Rutas cliente
    // --------------------------
    // Perfil del usuario (puede mostrar próximos y pasados eventos)
    Route::get('/perfil', [HomeController::class, 'perfil'])->name('user.profile');

    // Registro a un evento (modal)
    Route::post('/eventos/{evento}/registrar', [EventRegistrationController::class, 'store'])
        ->name('eventos.registrar');

    // Dejar reseña de un evento (solo si ya ocurrió)
    Route::post('/registros/{registro}/reseña', [EventRegistrationController::class, 'dejarReseña'])
        ->name('eventos.reseña');
});
