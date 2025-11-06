<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eventos;
use Illuminate\Support\Facades\Auth;

class EventoController extends Controller
{
    public function store(Request $request)
    {
        // Validar datos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'fecha' => 'required|date',
            'hora' => 'required',
            'direccion' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
        ]);

        // Crear el evento
        Eventos::create([
            'user_id' => Auth::id(),
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'direccion' => $request->direccion,
            'precio' => $request->precio,
        ]);

        // Redirigir con mensaje
        return redirect()->route('promotor.mis-eventos')->with('success', 'Evento creado correctamente.');
    }
}
