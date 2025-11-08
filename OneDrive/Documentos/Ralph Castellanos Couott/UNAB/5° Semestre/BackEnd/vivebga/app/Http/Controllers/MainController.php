<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eventos;
use Carbon\Carbon;

class MainController extends Controller
{
    public function index()
    {
        // Obtener solo eventos actuales y futuros
        $eventos = Eventos::whereDate('fecha', '>=', Carbon::today())
            ->orderBy('fecha', 'asc')
            ->get();

        return view('home', compact('eventos'));
    }
}
