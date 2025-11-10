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

    // 🏠 Página principal (eventos disponibles)
    public function index()
    {
        $eventos = Eventos::whereDate('fecha', '>=', Carbon::today())
            ->orderBy('fecha', 'asc')
            ->get();

        return view('main', compact('eventos'));
    }

    // 👤 PERFIL del cliente (solo información del usuario)
    public function perfil()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    // ✏️ FORMULARIO para editar el perfil
    public function editarPerfil()
    {
        $user = Auth::user();
        return view('user.edit', compact('user'));
    }

    // 💾 ACTUALIZAR perfil
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'telefono'  => 'nullable|string|max:20',
            'ciudad'    => 'nullable|string|max:100',
        ]);

        $user->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'telefono' => $request->telefono,
            'ciudad'   => $request->ciudad,
        ]);

        return redirect()->route('user.profile')->with('success', '✅ Perfil actualizado correctamente.');
    }

    // 🎟️ Muestra los eventos del cliente
    public function misEventos()
    {
        $user = Auth::user();
        $hoy = Carbon::today()->toDateString();

        // Buscar los eventos en los que está inscrito el cliente
        $registros = EventRegistration::with('evento')
            ->where('user_id', $user->id)
            ->get();

        // Separar los eventos según la fecha
        $eventos_proximos = $registros->filter(fn($r) => $r->evento->fecha >= $hoy);
        $eventos_pasados  = $registros->filter(fn($r) => $r->evento->fecha < $hoy);

        return view('user.events', compact('user', 'eventos_proximos', 'eventos_pasados'));
    }

    public function welcome()
    {
        return view('welcome');
    }
}
