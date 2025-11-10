<?php
use App\Http\Controllers\MapController;
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
    Route::get('/perfil', [HomeController::class, 'perfil'])->name('user.profile');
    Route::get('/perfil/editar', [HomeController::class, 'editarPerfil'])->name('user.edit');
    Route::post('/perfil/actualizar', [HomeController::class, 'update'])->name('user.update');
    Route::get('/perfil/eventos', [HomeController::class, 'misEventos'])->name('user.events');

    // Registro a un evento (modal)
    Route::post('/eventos/{evento}/registrar', [EventRegistrationController::class, 'store'])->name('eventos.registrar');
    Route::delete('/eventos/{id}/cancelar', [EventRegistrationController::class, 'cancelarInscripcion'])->name('eventos.cancelar');
    Route::post('/registros/{registro}/reseña', [EventRegistrationController::class, 'dejarResena'])->name('eventos.resena');

    // Mapa
    Route::get('/mapa-eventos', [MapController::class, 'index'])->name('mapa.eventos');

    // Cancelar un registro a un evento
    Route::delete('/eventos/{id}/cancelar', [EventRegistrationController::class, 'cancelarInscripcion'])->name('eventos.cancelar');

    // Dejar reseña de un evento (solo si ya ocurrió)
    Route::post('/registros/{registro}/reseña', [EventRegistrationController::class, 'dejarResena'])
        ->name('eventos.resena');


     // Barra de busqueda

    Route::get('/search', [EventoController::class, 'search'])->name('event.search');

    Route::get('/mapa-eventos', [MapController::class, 'index'])->name('mapa.eventos');

    // Perfil del cliente
    Route::get('/perfil', [HomeController::class, 'perfil'])->name('user.profile');

    
    // Mis eventos
    Route::get('/perfil/eventos', [HomeController::class, 'misEventos'])->name('user.events');
    
});
