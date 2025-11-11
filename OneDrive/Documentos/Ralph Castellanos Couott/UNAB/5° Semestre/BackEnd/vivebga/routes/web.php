<?php

use App\Http\Controllers\MapController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes();

// --------------------------
// Página principal con redirección por rol (requiere login)
// --------------------------
Route::middleware(['auth'])->get('/', function () {  
    $user = Auth::user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    // Cliente u organizador → página normal
    return app(HomeController::class)->index();
})->name('main');

// Redirección /home → /
Route::get('/home', fn() => redirect()->route('main'));

// --------------------------
// Rutas públicas
// --------------------------
Route::get('/eventos', [EventoController::class, 'index'])->name('events.index');
Route::get('/evento/{id}', [EventoController::class, 'show'])->name('events.detail');
Route::get('/organizer/{id}', [EventoController::class, 'perfilOrganizador'])->name('organizer.profile');

// --------------------------
// Rutas protegidas (solo usuarios logueados)
// --------------------------
Route::middleware(['auth'])->group(function () {

    // Rutas organizador
    Route::get('/eventos/crear', [EventoController::class, 'create'])->name('events.create');
    Route::post('/eventos', [EventoController::class, 'store'])->name('events.store');

    // Rutas cliente
    Route::get('/perfil', [HomeController::class, 'perfil'])->name('user.profile');
    Route::get('/perfil/editar', [HomeController::class, 'editarPerfil'])->name('user.edit');
    Route::post('/perfil/actualizar', [HomeController::class, 'update'])->name('user.update');
    Route::get('/perfil/eventos', [HomeController::class, 'misEventos'])->name('user.events');

    // Registro, cancelación y reseñas de eventos
    Route::post('/eventos/{evento}/registrar', [EventRegistrationController::class, 'store'])->name('eventos.registrar');
    Route::delete('/eventos/{id}/cancelar', [EventRegistrationController::class, 'cancelarInscripcion'])->name('eventos.cancelar');
    Route::post('/registros/{registro}/reseña', [EventRegistrationController::class, 'dejarResena'])->name('eventos.resena');

    // Mapa y búsqueda
    Route::get('/mapa-eventos', [MapController::class, 'index'])->name('mapa.eventos');
    Route::get('/search', [EventoController::class, 'search'])->name('event.search');
});

// --------------------------
// Rutas del administrador
// --------------------------
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
});
