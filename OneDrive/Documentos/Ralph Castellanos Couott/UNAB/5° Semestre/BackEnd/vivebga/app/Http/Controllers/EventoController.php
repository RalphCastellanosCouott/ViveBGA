<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eventos;
use App\Models\EventRegistration;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Illuminate\Support\Facades\Auth;

class EventoController extends Controller
{
    // 🔹 Muestra los eventos del organizador autenticado para su perfil
    public function perfilOrganizador($id)
    {
        $organizador = User::findOrFail($id);

        $hoy = now()->toDateString();

        $eventosProximos = Eventos::where('user_id', $organizador->id)
            ->where('fecha', '>=', $hoy)
            ->orderBy('fecha', 'asc')
            ->get();

        $eventosPasados = Eventos::where('user_id', $organizador->id)
            ->where('fecha', '<', $hoy)
            ->orderBy('fecha', 'desc')
            ->get();

        return view('organizer.profile', compact('organizador', 'eventosProximos', 'eventosPasados'));
    }

    // 🔹 Muestra el formulario de creación
    public function create()
    {
        if (Auth::user()->role !== 'organizador') {
            return redirect('/')
                ->with('error', 'Solo los organizadores pueden acceder a esta vista.');
        }

        return view('events.create');
    }

    // 🔹 Guarda el evento
    public function store(Request $request)
    {
        $request->validate(
            [
                'nombre' => 'required|string|max:255',
                'descripcion' => 'required|string',
                'fecha' => 'required|date',
                'hora' => 'required',
                'direccion' => 'required|string|max:255',
                'precio' => 'nullable|numeric|min:0',
                'imagen' => 'required|image|max:2048',
            ],
            [
                'imagen.required' => 'Debes subir una imagen para el evento.',
            ]
        );

        $evento = new Eventos($request->all());
        $evento->user_id = Auth::id();

        // Si el evento tiene cupos definidos, inicializa los cupos_disponibles
        if (!is_null($request->cupos) && $request->cupos > 0) {
            $evento->cupos_disponibles = $request->cupos;
        } else {
            // Si no tiene cupos limitados, deja el valor nulo
            $evento->cupos = null;
            $evento->cupos_disponibles = null;
        }

        if ($request->hasFile('imagen')) {
            $evento->imagen = $request->file('imagen')->store('eventos', 'public');
        }

        $evento->save();

        return redirect()->route('main')->with('success', 'Evento creado correctamente.');
    }

    // Muestra el detalle de un evento
    public function show($id)
    {
        $evento = Eventos::with('user')->findOrFail($id);
        $usuario = Auth::user();
        $registroUsuario = null;
        $usuarioAsistio = false;
        $eventoRealizado = false;

        $cuposDisponibles = !is_null($evento->cupos)
            ? $evento->cupos_disponibles
            : null;

        // Obtener registro del usuario (si está logueado)
        if ($usuario) {
            $registroUsuario = EventRegistration::where('user_id', $usuario->id)
                ->where('evento_id', $evento->id)
                ->first();

            $usuarioAsistio = $registroUsuario ? true : false;
        }

        $zonaHoraria = new CarbonTimeZone('America/Bogota');
        $fechaHoraEvento = Carbon::parse($evento->fecha . ' ' . $evento->hora, $zonaHoraria);
        $ahora = Carbon::now($zonaHoraria);
        $eventoRealizado = $ahora->greaterThanOrEqualTo($fechaHoraEvento->copy()->addMinutes(10));
        $registroBloqueado = $ahora->greaterThanOrEqualTo($fechaHoraEvento->copy()->addMinutes(15));
        $resenas = EventRegistration::where('evento_id', $evento->id)
            ->whereNotNull('resena')
            ->with('user')
            ->get();

        return view('events.detail', compact(
            'evento',
            'cuposDisponibles',
            'usuarioAsistio',
            'registroUsuario',
            'eventoRealizado',
            'resenas',
            'registroBloqueado'
        ));
    }    
}
