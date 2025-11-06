<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eventos;
use Illuminate\Support\Facades\Auth;

class PromotorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // protege todas las rutas
    }

    public function index()
    {
        return view('organizer.promotor');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'fecha' => 'required|date',
            'hora' => 'required',
            'direccion' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('eventos', 'public');
        }

        Eventos::create([
            'user_id' => Auth::id(),
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'direccion' => $request->direccion,
            'precio' => $request->precio,
            'imagen' => $path,
        ]);

        return redirect()->back()->with('success', 'Evento creado exitosamente.');
    }


public function listaEventos()
{
    // Obtiene el usuario autenticado
    $user = auth()->user();

    // Obtiene los eventos creados por el promotor logueado
    $eventos = \App\Models\Eventos::where('user_id', $user->id)
                ->orderBy('fecha', 'asc')
                ->get();

    return view('organizer.mis-eventos', compact('eventos', 'user'));
}

}