@extends('layouts.app')
@section('title', 'Admin · Partidos')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <div class="text-xs text-yellow-400 font-semibold mb-1">PANEL DE ADMINISTRACIÓN</div>
        <h1 class="text-2xl font-bold text-white">Gestión de Partidos</h1>
    </div>
    <div class="flex gap-3 flex-wrap">
        <a href="{{ route('admin.usuarios.index') }}" class="btn-primary" style="font-size:.875rem">Usuarios</a>
        <a href="{{ route('admin.equipos.index') }}" class="btn-primary" style="font-size:.875rem">Equipos</a>
        <a href="{{ route('admin.partidos.create') }}" class="btn-success text-sm">+ Nuevo partido</a>

        <form method="POST" action="{{ route('admin.api.importar') }}">
            @csrf
            <button type="submit" class="text-sm" style="background:#7c3aed;color:#fff;border-radius:8px;padding:8px 16px;font-weight:600;border:none;cursor:pointer">
                ↓ Importar partidos API
            </button>
        </form>

        <form method="POST" action="{{ route('admin.api.sincronizar') }}">
            @csrf
            <button type="submit" class="text-sm" style="background:#0369a1;color:#fff;border-radius:8px;padding:8px 16px;font-weight:600;border:none;cursor:pointer">
                ↻ Sincronizar resultados
            </button>
        </form>

        <form method="POST" action="{{ route('admin.api.nba') }}">
            @csrf
            <button type="submit" class="text-sm" style="background:#b45309;color:#fff;border-radius:8px;padding:8px 16px;font-weight:600;border:none;cursor:pointer">
                🏀 Importar NBA
            </button>
        </form>
    </div>
</div>

<div class="card overflow-hidden">
    <table>
        <thead>
            <tr>
                <th>Partido</th>
                <th>Liga</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Resultado</th>
                <th>Cuotas</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($partidos as $partido)
            <tr>
                <td>
                    <div class="font-semibold text-white text-sm">
                        {{ $partido->equipoLocal->nombre }} vs {{ $partido->equipoVisitante->nombre }}
                    </div>
                    <div class="text-xs text-slate-500">{{ $partido->jornada }}</div>
                </td>
                <td class="text-slate-300 text-sm">{{ $partido->liga->nombre }}</td>
                <td class="text-slate-300 text-sm">{{ $partido->fecha->format('d/m/Y H:i') }}</td>
                <td>
                    <span class="badge badge-{{ $partido->estado }}">{{ ucfirst(str_replace('_',' ',$partido->estado)) }}</span>
                </td>
                <td class="text-slate-300 text-sm">
                    @if($partido->estado === 'finalizado')
                        {{ $partido->goles_local }} - {{ $partido->goles_visitante }}
                    @else
                        —
                    @endif
                </td>
                <td class="text-xs text-slate-400">
                    @if($partido->cuota)
                        1: {{ $partido->cuota->cuota_local }} /
                        X: {{ $partido->cuota->cuota_empate }} /
                        2: {{ $partido->cuota->cuota_visitante }}
                    @endif
                </td>
                <td>
                    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
                        <a href="{{ route('admin.partidos.edit', $partido) }}"
                           style="color:#38bdf8;font-size:.8rem;text-decoration:none">Cuotas</a>

                        @if($partido->estado === 'pendiente')
                        <a href="{{ route('admin.partidos.resultado.form', $partido) }}"
                           style="background:#16a34a;color:#fff;border-radius:6px;padding:4px 10px;font-size:.8rem;font-weight:600;text-decoration:none">
                            ✓ Resultado
                        </a>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="p-4">
        {{ $partidos->links() }}
    </div>
</div>
@endsection
