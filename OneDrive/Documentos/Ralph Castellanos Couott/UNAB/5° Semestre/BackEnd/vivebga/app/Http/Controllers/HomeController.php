<?php

namespace App\Http\Controllers;

use App\Models\Eventos;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Página principal con eventos próximos
    public function index()
    {
        $eventos = Eventos::whereDate('fecha', '>=', Carbon::today())
            ->orderBy('fecha', 'asc')
            ->get();

        return view('main', compact('eventos'));
    }

    // Perfil único según rol
    public function perfil()
    {
        $user = Auth::user();
        $hoy = Carbon::today()->toDateString();

        if ($user->role === 'organizador') {
            // Eventos creados por el organizador
            $eventos_proximos = Eventos::where('user_id', $user->id)
                ->where('fecha', '>=', $hoy)
                ->orderBy('fecha', 'asc')
                ->get();

            $eventos_pasados = Eventos::where('user_id', $user->id)
                ->where('fecha', '<', $hoy)
                ->orderBy('fecha', 'desc')
                ->get();
        } else {
            // Eventos en los que está registrado el cliente
            $registros = EventRegistration::with('evento')
                ->where('user_id', $user->id)
                ->get();

            $eventos_proximos = $registros->filter(function ($registro) use ($hoy) {
                return $registro->evento->fecha >= $hoy;
            });

            $eventos_pasados = $registros->filter(function ($registro) use ($hoy) {
                return $registro->evento->fecha < $hoy;
            });
        }

        return view('user.profile', compact('user', 'eventos_proximos', 'eventos_pasados'));
    }

    public function welcome()
    {
        return view('welcome');
    }
}
