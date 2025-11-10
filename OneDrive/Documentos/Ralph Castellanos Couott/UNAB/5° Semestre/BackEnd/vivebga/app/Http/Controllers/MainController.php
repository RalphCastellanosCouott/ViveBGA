<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eventos;
use Carbon\Carbon;

class MainController extends Controller
{
    public function index(Request $request)
    {
        $query = Eventos::query()
            ->whereDate('fecha', '>=', Carbon::today());

        // Filtro opcional por nombre o descripción
        if ($request->filled('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%')
                ->orWhere('descripcion', 'like', '%' . $request->search . '%');
        }

        $eventos = $query->orderBy('fecha', 'asc')->get();
        
        return view('main', compact('eventos'));
    }
}
