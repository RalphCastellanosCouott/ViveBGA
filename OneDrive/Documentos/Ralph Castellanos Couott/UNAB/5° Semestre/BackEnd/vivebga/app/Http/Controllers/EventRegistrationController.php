<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eventos;
use App\Models\EventRegistration;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Illuminate\Support\Facades\Auth;

class EventRegistrationController extends Controller
{
    // 🔹 Registrarse a un evento
    public function store(Request $request, $eventoId)
    {
        $evento = Eventos::findOrFail($eventoId);

        // Validamos la cantidad de cupos que el cliente quiere
        $request->validate([
            'cantidad' => 'required|integer|min:1',
        ]);

        // Verificamos que el usuario no se haya registrado ya
        $existe = EventRegistration::where('user_id', Auth::id())
            ->where('evento_id', $evento->id)
            ->first();

        if ($existe) {
            return redirect()->back()->with('error', 'Ya estás registrado en este evento.');
        }

        // Si el evento tiene cupos limitados, validamos disponibilidad
        if (!is_null($evento->cupos_disponibles)) {
            if ($request->cantidad > $evento->cupos_disponibles) {
                return redirect()->back()->with('error', 'No hay suficientes cupos disponibles.');
            }

            // Restamos los cupos adquiridos
            $evento->cupos_disponibles -= $request->cantidad;
            $evento->save();
        }

        // Creamos el registro
        EventRegistration::create([
            'user_id' => Auth::id(),
            'evento_id' => $evento->id,
            'cantidad' => $request->cantidad,
            'precio_pagado' => ($evento->precio ?? 0) * $request->cantidad,
        ]);

        return redirect()->back()->with('success', 'Te has registrado correctamente al evento.');
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

        // Zona horaria y hora del evento
        $zona = new \Carbon\CarbonTimeZone('America/Bogota');
        $inicioEvento = \Carbon\Carbon::parse($evento->fecha . ' ' . $evento->hora, $zona);

        // Verifica que el evento no haya iniciado (ni pasen 15 minutos)
        if (now($zona)->gte($inicioEvento->copy()->addMinutes(15))) {
            return redirect()->back()->with('error', 'No puedes cancelar la inscripción después de iniciado el evento.');
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

    // 🔹 Mostrar los eventos en los que el cliente está registrado
    public function misRegistros()
    {
        $registros = EventRegistration::with('evento')
            ->where('user_id', Auth::id())
            ->get();

        return view('user.registered_events', compact('registros'));
    }

    // 🔹 Dejar reseña sobre un evento (solo si ya ocurrió)
    public function dejarResena(Request $request, $registroId)
    {
        $registro = EventRegistration::findOrFail($registroId);

        // Validar que el usuario sea dueño de la inscripción
        if ($registro->user_id !== Auth::id()) {
            abort(403);
        }

        // Validar que el evento ya haya pasado (fecha + hora)
        $zonaHoraria = new CarbonTimeZone('America/Bogota');
        $evento = $registro->evento;
        $fechaHoraEvento = Carbon::parse($evento->fecha . ' ' . $evento->hora, $zonaHoraria);

        if (now()->lessThan($fechaHoraEvento->addMinutes(10))) {
            return redirect()->back()->with('error', 'Solo puedes dejar una reseña después de que el evento haya ocurrido.');
        }

        // Validar campos del formulario
        $request->validate([
            'calificacion' => 'required|integer|min:1|max:5',
            'resena' => 'required|string|max:1000',
        ]);

        // Guardar reseña
        $registro->update([
            'calificacion' => $request->calificacion,
            'resena' => $request->resena,
        ]);

        return redirect()->back()->with('success', '¡Tu reseña ha sido registrada exitosamente!');
    }
}
