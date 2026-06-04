@extends('layouts.app')
@section('title', 'Mis Apuestas')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-white">Mis Apuestas</h1>
    <div class="text-right">
        <div class="text-slate-400 text-sm">Saldo actual</div>
        <div class="text-2xl font-bold text-green-400">{{ number_format(auth()->user()->saldo, 2) }} €</div>
    </div>
</div>

@if($apuestas->isEmpty())
    <div class="card p-8 text-center">
        <p class="text-slate-400 mb-4">Todavía no has realizado ninguna apuesta.</p>
        <a href="{{ route('partidos.index') }}" class="btn-primary">Ver partidos disponibles</a>
    </div>
@else
    {{-- Resumen --}}
    @php
        $ganadas  = $apuestas->where('estado', 'ganada')->count();
        $perdidas = $apuestas->where('estado', 'perdida')->count();
        $pendientes = $apuestas->where('estado', 'pendiente')->count();
        $gananciasTotal = $apuestas->where('estado', 'ganada')->sum('ganancia_potencial');
    @endphp
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="card p-4 text-center">
            <div class="text-2xl font-bold text-white">{{ $apuestas->count() }}</div>
            <div class="text-xs text-slate-400 mt-1">Total apuestas</div>
        </div>
        <div class="card p-4 text-center">
            <div class="text-2xl font-bold text-yellow-400">{{ $pendientes }}</div>
            <div class="text-xs text-slate-400 mt-1">Pendientes</div>
        </div>
        <div class="card p-4 text-center">
            <div class="text-2xl font-bold text-green-400">{{ $ganadas }}</div>
            <div class="text-xs text-slate-400 mt-1">Ganadas</div>
        </div>
        <div class="card p-4 text-center">
            <div class="text-2xl font-bold text-red-400">{{ $perdidas }}</div>
            <div class="text-xs text-slate-400 mt-1">Perdidas</div>
        </div>
    </div>

    <div class="card overflow-hidden">
        <table>
            <thead>
                <tr>
                    <th>Partido</th>
                    <th>Apuesta</th>
                    <th>Cantidad</th>
                    <th>Cuota</th>
                    <th>Ganancia potencial</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach($apuestas as $apuesta)
                <tr>
                    <td>
                        <div class="font-medium text-white text-sm">
                            {{ $apuesta->partido->equipoLocal->nombre }}
                            <span class="text-slate-500">vs</span>
                            {{ $apuesta->partido->equipoVisitante->nombre }}
                        </div>
                        <div class="text-xs text-slate-500">{{ $apuesta->partido->liga->nombre }}</div>
                    </td>
                    <td class="font-semibold text-slate-300">
                        @if($apuesta->tipo === 'local') 1 (Local)
                        @elseif($apuesta->tipo === 'empate') X (Empate)
                        @else 2 (Visitante)
                        @endif
                    </td>
                    <td class="text-white">{{ number_format($apuesta->cantidad, 2) }} €</td>
                    <td class="text-sky-400 font-bold">x{{ $apuesta->cuota }}</td>
                    <td class="text-green-400 font-semibold">{{ number_format($apuesta->ganancia_potencial, 2) }} €</td>
                    <td>
                        @php
                            $clases = ['ganada'=>'text-green-400','perdida'=>'text-red-400','pendiente'=>'text-yellow-400','cancelada'=>'text-slate-500'];
                            $iconos = ['ganada'=>'✓','perdida'=>'✗','pendiente'=>'⏳','cancelada'=>'—'];
                        @endphp
                        <span class="{{ $clases[$apuesta->estado] ?? '' }} font-semibold text-sm">
                            {{ $iconos[$apuesta->estado] ?? '' }} {{ ucfirst($apuesta->estado) }}
                        </span>
                    </td>
                    <td class="text-slate-400 text-sm">{{ $apuesta->created_at->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
