<?php

namespace App\Http\Controllers;

use App\Models\Apuesta;
use App\Models\Partido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApuestaController extends Controller
{
    public function index()
    {
        $apuestas = Auth::user()->apuestas()
            ->with(['partido.equipoLocal', 'partido.equipoVisitante', 'partido.liga'])
            ->orderByDesc('created_at')
            ->get();

        return view('apuestas.index', compact('apuestas'));
    }

    public function store(Request $request, Partido $partido)
    {
        $request->validate([
            'tipo'     => 'required|in:local,empate,visitante',
            'cantidad' => 'required|numeric|min:1|max:10000',
        ]);

        $user = Auth::user();

        if ($partido->estado !== 'pendiente') {
            return back()->with('error', 'Este partido ya no admite apuestas.');
        }

        if ($user->saldo < $request->cantidad) {
            return back()->with('error', 'Saldo insuficiente.');
        }

        // Comprobar si ya apostó en este partido
        $yaAposto = Apuesta::where('user_id', $user->id)
            ->where('partido_id', $partido->id)
            ->exists();

        if ($yaAposto) {
            return back()->with('error', 'Ya has realizado una apuesta en este partido.');
        }

        $cuotaValor = $partido->cuota->{'cuota_' . $request->tipo};
        $ganancia   = round($request->cantidad * $cuotaValor, 2);

        Apuesta::create([
            'user_id'            => $user->id,
            'partido_id'         => $partido->id,
            'tipo'               => $request->tipo,
            'cantidad'           => $request->cantidad,
            'cuota'              => $cuotaValor,
            'ganancia_potencial' => $ganancia,
            'estado'             => 'pendiente',
        ]);

        $user->decrement('saldo', $request->cantidad);

        return redirect()->route('apuestas.index')
            ->with('success', "Apuesta realizada. Ganancia potencial: {$ganancia} €");
    }
}
