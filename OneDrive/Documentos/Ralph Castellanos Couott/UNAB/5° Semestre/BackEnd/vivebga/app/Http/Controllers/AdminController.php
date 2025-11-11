<?php

namespace App\Http\Controllers;

use App\Models\Eventos;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
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

    public function clientes()
    {
        $clientes = User::where('role', 'cliente')->get();
        return view('admin.clientes', compact('clientes'));
    }

    public function organizadores()
    {
        $organizadores = User::where('role', 'organizador')
            ->withCount('eventos')
            ->get();

        return view('admin.organizadores', compact('organizadores'));
    }

    public function eventos()
    {
        $zonaHoraria = new CarbonTimeZone('America/Bogota');

        $eventos = Eventos::with('user')
            ->withCount('registros')
            ->get()
            ->map(function ($evento) use ($zonaHoraria) {
                $fechaHoraEvento = Carbon::parse($evento->fecha . ' ' . $evento->hora, $zonaHoraria);
                $evento->estado = Carbon::now($zonaHoraria)->greaterThanOrEqualTo($fechaHoraEvento->copy()->addHours(2))
                    ? 'Pasado'
                    : 'Activo';
                return $evento;
            });

        return view('admin.eventos', compact('eventos'));
    }

    public function permisos()
    {
        $organizadores = User::where('role', 'organizador')->get();
        return view('admin.permisos', compact('organizadores'));
    }

    public function cambiarPermiso($id)
    {
        $organizador = User::findOrFail($id);
        $organizador->permiso_evento = !$organizador->permiso_evento;
        $organizador->save();

        return redirect()->back()->with('success', 'Permiso actualizado correctamente.');
    }

    public function eliminarUsuario($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();

        return redirect()->back()->with('success', 'Usuario eliminado correctamente.');
    }

    public function eliminarEvento($id)
    {
        $evento = Eventos::findOrFail($id);
        $evento->delete();

        return redirect()->back()->with('success', 'Evento eliminado correctamente.');
    }
}
