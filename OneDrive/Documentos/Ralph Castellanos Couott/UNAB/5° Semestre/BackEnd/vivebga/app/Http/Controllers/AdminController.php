<?php

namespace App\Http\Controllers;

use App\Models\Eventos;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Zona horaria
        date_default_timezone_set('America/Bogota');
        $hoy = Carbon::now()->toDateString();
        // Contadores básicos
        $clientes = User::where('role', 'cliente')->count();
        $organizadores = User::where('role', 'organizador')->count();
        $eventosActivos = Eventos::where('fecha', '>=', $hoy)->count();
        $totalEventos = Eventos::count();

        return view('admin.dashboard', compact(
            'clientes',
            'organizadores',
            'eventosActivos',
            'totalEventos'
        ));
    }
}
