<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    EventoController,
    MapController,
    EventRegistrationController,
    AdminController
};

// --------------------------------------------------
// Rutas de autenticación (login, register, logout)
// --------------------------------------------------
Auth::routes();

// --------------------------------------------------
// Bloqueo total: el sitio requiere login para todo
// --------------------------------------------------
Route::middleware(['auth'])->group(function () {

    // --------------------------
    // Página principal (redirección según rol)
    // --------------------------
    Route::get('/', function () {
        $user = Auth::user();

        // Si es admin → redirige al dashboard
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Cliente u organizador → página principal normal
        return app(HomeController::class)->index();
    })->name('main');

    // Alias /home → /
    Route::get('/home', fn() => redirect()->route('main'));

    // --------------------------
    // Rutas generales
    // --------------------------
    Route::get('/eventos', [EventoController::class, 'index'])->name('events.index');
    Route::get('/evento/{id}', [EventoController::class, 'show'])->name('events.detail');
    Route::get('/organizer/{id}', [EventoController::class, 'perfilOrganizador'])->name('organizer.profile');
    Route::get('/mapa-eventos', [MapController::class, 'index'])->name('mapa.eventos');
    Route::get('/search', [EventoController::class, 'search'])->name('event.search');

    // --------------------------
    // Perfil del usuario
    // --------------------------
    Route::prefix('perfil')->group(function () {
        Route::get('/', [HomeController::class, 'perfil'])->name('user.profile');
        Route::get('/editar', [HomeController::class, 'editarPerfil'])->name('user.edit');
        Route::post('/actualizar', [HomeController::class, 'update'])->name('user.update');
        Route::get('/eventos', [HomeController::class, 'misEventos'])->name('user.events');
    });

    // --------------------------
    // Eventos (organizador y cliente)
    // --------------------------
    Route::prefix('eventos')->group(function () {
        Route::get('/crear', [EventoController::class, 'create'])->name('events.create');
        Route::post('/', [EventoController::class, 'store'])->name('events.store');
        Route::post('/{evento}/registrar', [EventRegistrationController::class, 'store'])->name('eventos.registrar');
        Route::delete('/{id}/cancelar', [EventRegistrationController::class, 'cancelarInscripcion'])->name('eventos.cancelar');
    });

    Route::post('/registros/{registro}/reseña', [EventRegistrationController::class, 'dejarResena'])
        ->name('eventos.resena');

    // --------------------------
    // Rutas del administrador
    // --------------------------
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    });
});
