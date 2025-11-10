<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eventos;
use App\Models\EventRegistration;
use App\Models\User;
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
        $evento = Eventos::withCount('registros')->findOrFail($id);
        $usuarioAsistio = false;
        $registroUsuario = null;
        $hoy = now()->toDateString();

        if (Auth::check()) {
            $registro = $evento->registros()->where('user_id', Auth::id())->first();
            if ($registro) {
                $usuarioAsistio = true;
                $registroUsuario = $registro;
            }
        }

        $eventoRealizado = $usuarioAsistio && ($evento->fecha <= $hoy);

        $totalRegistrados = $evento->registros()->sum('cantidad');

        if (!is_null($evento->cupos)) {
            $cuposDisponibles = max(($evento->cupos ?? 0) - $totalRegistrados, 0);
        } else {
            $cuposDisponibles = null; // Evento sin límite
        }

        return view('events.detail', compact(
            'evento',
            'usuarioAsistio',
            'cuposDisponibles',
            'registroUsuario',
            'eventoRealizado'
        ));
    }

    public function cancelarInscripcion($id)
    {
        $evento = Eventos::findOrFail($id);
        $user = Auth::user();

        // Verifica si el usuario estaba inscrito
        $registro = EventRegistration::where('evento_id', $evento->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$registro) {
            return redirect()->back()->with('error', 'No estás registrado en este evento.');
        }       

        // Si el evento tiene cupos limitados, libera un cupo
        if (!is_null($evento->cupos_disponibles)) {
            $evento->cupos_disponibles += $registro->cantidad;
            $evento->save();
        }

        // Elimina el registro
        $registro->delete();

        return redirect()->back()->with('success', 'Tu registro ha sido cancelado correctamente.');
    }
}
