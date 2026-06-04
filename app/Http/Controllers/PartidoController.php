<?php

namespace App\Http\Controllers;

use App\Models\Liga;
use App\Models\Partido;
use Illuminate\Http\Request;

class PartidoController extends Controller
{
    public function index(Request $request)
    {
        $ligas = Liga::where('activa', true)->get();

        $query = Partido::with(['equipoLocal', 'equipoVisitante', 'liga', 'cuota'])
            ->where('estado', 'pendiente')
            ->orderBy('fecha');

        if ($request->filled('liga')) {
            $query->where('liga_id', $request->liga);
        }

        $partidos = $query->get();

        return view('partidos.index', compact('partidos', 'ligas'));
    }

    public function show(Partido $partido)
    {
        $partido->load(['equipoLocal', 'equipoVisitante', 'liga', 'cuota']);
        return view('partidos.show', compact('partido'));
    }
}
