<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eventos;
use App\Models\EventRegistration;
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

    // 🔹 Mostrar los eventos en los que el cliente está registrado
    public function misRegistros()
    {
        $registros = EventRegistration::with('evento')
            ->where('user_id', Auth::id())
            ->get();

        return view('user.registered_events', compact('registros'));
    }

    // 🔹 Dejar reseña sobre un evento (solo si ya ocurrió)
    public function dejarReseña(Request $request, $registroId)
    {
        $registro = EventRegistration::findOrFail($registroId);

        // Validamos que el usuario sea el dueño
        if ($registro->user_id !== Auth::id()) {
            abort(403);
        }

        // Validamos que el evento ya haya pasado
        if ($registro->evento->fecha > now()->toDateString()) {
            return redirect()->back()->with('error', 'Solo puedes dejar reseña de eventos que ya ocurrieron.');
        }

        $request->validate([
            'reseña' => 'required|string|max:1000',
        ]);

        $registro->reseña = $request->reseña;
        $registro->save();

        return redirect()->back()->with('success', 'Reseña guardada correctamente.');
    }
}
