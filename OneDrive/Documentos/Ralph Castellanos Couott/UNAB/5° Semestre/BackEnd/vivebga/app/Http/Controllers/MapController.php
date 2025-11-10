<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eventos;
use Carbon\Carbon;

class MapController extends Controller
{
    public function index()
{
    $hoy = now()->toDateString();
    $eventos = \App\Models\Eventos::where('fecha', '>=', $hoy)
        ->whereNotNull('lat')
        ->whereNotNull('lng')
        ->orderBy('fecha', 'asc')
        ->get();

    // Preparamos solo los campos necesarios
    $eventosMap = $eventos->map(function ($e) {
        return [
            'id' => $e->id,
            'nombre' => $e->nombre,
            'fecha' => $e->fecha,
            'hora' => $e->hora,
            'precio' => $e->precio,
            'direccion' => $e->direccion,
            'imagen' => $e->imagen ? asset('storage/' . $e->imagen) : asset('images/default-event.jpg'),
            'lat' => (float) $e->lat,
            'lng' => (float) $e->lng,
            'url' => route('events.detail', ['id' => $e->id]),
        ];
    });

    // enviamos $eventosMap (ya simplificado)
    return view('events.map', [
        'eventosMap' => $eventosMap,
    ]);
}

}
