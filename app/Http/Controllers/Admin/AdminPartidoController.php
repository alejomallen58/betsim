<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cuota;
use App\Models\Equipo;
use App\Models\Liga;
use App\Models\Partido;
use Illuminate\Http\Request;

class AdminPartidoController extends Controller
{
    private function soloAdmin(): void
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Acceso restringido al panel de administración.');
        }
    }

    public function index()
    {
        $this->soloAdmin();

        $partidos = Partido::with(['equipoLocal', 'equipoVisitante', 'liga'])
            ->orderBy('estado')        // pendiente primero, finalizado después
            ->orderBy('fecha')         // dentro de cada estado, más próximo arriba
            ->paginate(15);

        return view('admin.partidos.index', compact('partidos'));
    }

    public function create()
    {
        $this->soloAdmin();

        $ligas   = Liga::where('activa', true)->get();
        $equipos = Equipo::with('liga')->orderBy('nombre')->get();
        return view('admin.partidos.create', compact('ligas', 'equipos'));
    }

    public function store(Request $request)
    {
        $this->soloAdmin();

        $request->validate([
            'liga_id'             => 'required|exists:ligas,id',
            'equipo_local_id'     => 'required|exists:equipos,id|different:equipo_visitante_id',
            'equipo_visitante_id' => 'required|exists:equipos,id',
            'fecha'               => 'required|date',
            'jornada'             => 'nullable|string|max:50',
            'cuota_local'         => 'required|numeric|min:1.01',
            'cuota_empate'        => 'required|numeric|min:1.01',
            'cuota_visitante'     => 'required|numeric|min:1.01',
        ]);

        $partido = Partido::create($request->only(
            'liga_id', 'equipo_local_id', 'equipo_visitante_id', 'fecha', 'jornada'
        ));

        Cuota::create([
            'partido_id'      => $partido->id,
            'cuota_local'     => $request->cuota_local,
            'cuota_empate'    => $request->cuota_empate,
            'cuota_visitante' => $request->cuota_visitante,
        ]);

        return redirect()->route('admin.partidos.index')
            ->with('success', 'Partido creado correctamente.');
    }

    public function edit(Partido $partido)
    {
        $this->soloAdmin();

        $partido->load('cuota');
        $ligas   = Liga::where('activa', true)->get();
        $equipos = Equipo::with('liga')->orderBy('nombre')->get();
        return view('admin.partidos.edit', compact('partido', 'ligas', 'equipos'));
    }

    public function update(Request $request, Partido $partido)
    {
        $this->soloAdmin();

        $request->validate([
            'cuota_local'     => 'required|numeric|min:1.01',
            'cuota_empate'    => 'required|numeric|min:1.01',
            'cuota_visitante' => 'required|numeric|min:1.01',
        ]);

        $partido->cuota->update([
            'cuota_local'     => $request->cuota_local,
            'cuota_empate'    => $request->cuota_empate,
            'cuota_visitante' => $request->cuota_visitante,
        ]);

        return redirect()->route('admin.partidos.index')
            ->with('success', 'Cuotas actualizadas.');
    }

    public function resultadoForm(Partido $partido)
    {
        $this->soloAdmin();

        if ($partido->estado !== 'pendiente') {
            return redirect()->route('admin.partidos.index')
                ->with('error', 'Este partido ya ha sido finalizado.');
        }

        $partido->load(['equipoLocal', 'equipoVisitante', 'liga']);
        return view('admin.partidos.resultado', compact('partido'));
    }

    public function resultado(Request $request, Partido $partido)
    {
        $this->soloAdmin();

        $request->validate([
            'goles_local'     => 'required|integer|min:0|max:20',
            'goles_visitante' => 'required|integer|min:0|max:20',
        ]);

        $partido->update([
            'goles_local'     => $request->goles_local,
            'goles_visitante' => $request->goles_visitante,
            'estado'          => 'finalizado',
        ]);

        // Determinar ganador
        if ($request->goles_local > $request->goles_visitante) {
            $ganador = 'local';
        } elseif ($request->goles_local < $request->goles_visitante) {
            $ganador = 'visitante';
        } else {
            $ganador = 'empate';
        }

        // Resolver apuestas
        foreach ($partido->apuestas()->where('estado', 'pendiente')->get() as $apuesta) {
            if ($apuesta->tipo === $ganador) {
                $apuesta->update(['estado' => 'ganada']);
                $apuesta->user->increment('saldo', $apuesta->ganancia_potencial);
            } else {
                $apuesta->update(['estado' => 'perdida']);
            }
        }

        return redirect()->route('admin.partidos.index')
            ->with('success', 'Resultado registrado y apuestas resueltas.');
    }
}
