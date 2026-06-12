@extends('layouts.app')
@section('title', 'Partidos')

@section('content')
<style>
    .partidos-header  { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
    .partidos-header h1 { font-size: 1.5rem; font-weight: 700; color: #fff; margin: 0; }

    .partido-card          { background: #1e293b; border: 1px solid #334155; border-radius: 12px;
                             padding: 20px 24px; margin-bottom: 12px; }
    .partido-card.finalizado { opacity: .75; border-color: #1e3a1e; }
    .partido-inner         { display: flex; align-items: center; justify-content: space-between;
                             flex-wrap: wrap; gap: 16px; }
    .partido-info          { flex: 1; min-width: 180px; }
    .partido-meta          { font-size: .73rem; color: #64748b; margin-bottom: 6px; }
    .partido-teams         { font-size: 1.05rem; font-weight: 700; color: #fff; }
    .partido-teams span    { color: #475569; margin: 0 8px; font-weight: 400; }
    .partido-date          { font-size: .82rem; color: #94a3b8; margin-top: 5px; }
    .resultado-texto       { font-size: 1.4rem; font-weight: 800; color: #4ade80; letter-spacing: 2px; }

    .partido-cuotas   { display: flex; gap: 10px; }

    .empty-state      { background: #1e293b; border: 1px solid #334155; border-radius: 12px;
                        padding: 48px; text-align: center; color: #64748b; }
</style>

<div class="partidos-header">
    <h1>Partidos</h1>
    <form method="GET" action="{{ route('partidos.index') }}">
        <select name="liga" onchange="this.form.submit()" style="width:auto;padding:8px 12px">
            <option value="">Todas las ligas</option>
            @foreach($ligas as $liga)
                <option value="{{ $liga->id }}" {{ request('liga') == $liga->id ? 'selected' : '' }}>
                    {{ $liga->nombre }}
                </option>
            @endforeach
        </select>
    </form>
</div>

@if($partidos->isEmpty())
    <div class="empty-state">No hay partidos disponibles en este momento.</div>
@else
    @foreach($partidos as $partido)
    <div class="partido-card {{ $partido->estado === 'finalizado' ? 'finalizado' : '' }}">
        <div class="partido-inner">

            <div class="partido-info">
                <div class="partido-meta">
                    {{ $partido->liga->nombre }} · {{ $partido->jornada }}
                    @if($partido->estado === 'finalizado')
                        &nbsp;<span class="badge badge-finalizado">Finalizado</span>
                    @endif
                </div>
                <div class="partido-teams">
                    {{ $partido->equipoLocal->nombre }}
                    <span>vs</span>
                    {{ $partido->equipoVisitante->nombre }}
                </div>
                <div class="partido-date">📅 {{ $partido->fecha->format('d/m/Y H:i') }}</div>
            </div>

            @if($partido->estado === 'finalizado')
                <div class="resultado-texto">
                    {{ $partido->goles_local }} – {{ $partido->goles_visitante }}
                </div>
            @else
                @if($partido->cuota)
                <div class="partido-cuotas">
                    <div class="cuota-box">
                        <div class="valor">{{ $partido->cuota->cuota_local }}</div>
                        <div class="label">1 Local</div>
                    </div>
                    <div class="cuota-box">
                        <div class="valor">{{ $partido->cuota->cuota_empate }}</div>
                        <div class="label">X Empate</div>
                    </div>
                    <div class="cuota-box">
                        <div class="valor">{{ $partido->cuota->cuota_visitante }}</div>
                        <div class="label">2 Visit.</div>
                    </div>
                </div>
                @endif
                <a href="{{ route('partidos.show', $partido) }}" class="btn-primary" style="white-space:nowrap;font-size:.875rem">
                    Apostar →
                </a>
            @endif
        </div>
    </div>
    @endforeach
@endif
@endsection
