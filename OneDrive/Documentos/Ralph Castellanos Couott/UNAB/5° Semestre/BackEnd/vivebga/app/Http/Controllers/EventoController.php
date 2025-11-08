<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eventos;
use Illuminate\Support\Facades\Auth;

class EventoController extends Controller
{
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'organizador') {
            return redirect()
                ->route('home')
                ->with('error', 'Solo los organizadores pueden crear eventos.');
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'fecha' => 'required|date',
            'hora' => 'required',
            'direccion' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Ruta por defecto si no sube imagen
        $rutaImagen = null;

        // Si se subió una imagen, guardarla en storage/app/public/eventos
        if ($request->hasFile('imagen')) {
            $rutaImagen = $request->file('imagen')->store('eventos', 'public');
        }

        // Crear evento con imagen incluida
        Eventos::create([
            'user_id' => Auth::id(),
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'direccion' => $request->direccion,
            'precio' => $request->precio,
            'imagen' => $rutaImagen,
        ]);

        return redirect()
            ->route('mis-eventos')
            ->with('success', 'Evento creado correctamente.');
    }

    public function show($id)
    {
        $evento = Eventos::findOrFail($id);
        return view('events.detail', compact('evento'));
    }
    public function index()
    {
        $hoy = now()->toDateString();

        // Mostrar solo eventos que aún no han pasado
        $eventos = \App\Models\Eventos::where('fecha', '>=', $hoy)
            ->orderBy('fecha', 'asc')
            ->get();

        return view('events.index', compact('eventos'));
    }
};
