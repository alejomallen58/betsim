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

        $hace2dias = now()->subDays(2);

        $query = Partido::with(['equipoLocal', 'equipoVisitante', 'liga', 'cuota'])
            ->where(function ($q) use ($hace2dias) {
                $q->where('estado', 'pendiente')
                  ->orWhere(function ($q2) use ($hace2dias) {
                      $q2->where('estado', 'finalizado')
                         ->where('fecha', '>=', $hace2dias);
                  });
            })
            ->orderByRaw("CASE WHEN estado = 'pendiente' THEN 0 ELSE 1 END")
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
