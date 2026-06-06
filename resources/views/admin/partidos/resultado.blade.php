@extends('layouts.app')
@section('title', 'Registrar Resultado')

@section('content')
<style>
    .res-wrap       { max-width: 520px; margin: 0 auto; }
    .res-card       { background: #1e293b; border: 1px solid #334155; border-radius: 12px; padding: 32px 28px; }
    .res-match      { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 32px; }
    .res-team       { flex: 1; text-align: center; }
    .res-team-name  { font-size: 1.1rem; font-weight: 700; color: #fff; word-break: break-word; }
    .res-team-role  { font-size: .75rem; color: #64748b; margin-top: 4px; }
    .res-vs         { font-size: 1.1rem; color: #475569; font-weight: 700; }
    .res-meta       { font-size: .75rem; color: #64748b; text-align: center; margin-bottom: 24px; }

    .score-grid     { display: grid; grid-template-columns: 1fr 60px 1fr; align-items: center; gap: 12px; margin-bottom: 28px; }
    .score-label    { font-size: .8rem; color: #94a3b8; text-align: center; margin-bottom: 6px; }
    .score-input    { background: #0f172a !important; border: 2px solid #334155 !important;
                      color: #fff !important; border-radius: 10px !important;
                      padding: 14px 8px !important; font-size: 2rem !important;
                      font-weight: 700 !important; text-align: center !important;
                      width: 100% !important; box-sizing: border-box; }
    .score-input:focus  { border-color: #2563eb !important; outline: none !important; }
    .score-dash     { text-align: center; font-size: 1.8rem; font-weight: 700; color: #475569; }

    .apuestas-info  { background: #0f172a; border: 1px solid #334155; border-radius: 8px;
                      padding: 12px 16px; margin-bottom: 24px; font-size: .82rem; color: #94a3b8; }
    .apuestas-info strong { color: #fbbf24; }

    .btn-registrar  { width: 100%; background: #16a34a; color: #fff; border: none; border-radius: 8px;
                      padding: 13px; font-size: 1rem; font-weight: 700; cursor: pointer; }
    .btn-registrar:hover { background: #15803d; }
    .back-link      { color: #64748b; text-decoration: none; font-size: .875rem;
                      display: inline-block; margin-bottom: 14px; }
    .back-link:hover { color: #fff; }
    .page-title     { font-size: 1.3rem; font-weight: 700; color: #fff; margin: 0 0 20px 0; }
</style>

<div class="res-wrap">
    <a href="{{ route('admin.partidos.index') }}" class="back-link">← Volver a partidos</a>
    <h1 class="page-title">Registrar Resultado</h1>

    <div class="res-card">

        {{-- Cabecera del partido --}}
        <div class="res-meta">{{ $partido->liga->nombre }} · {{ $partido->jornada }} · {{ $partido->fecha->format('d/m/Y H:i') }}</div>

        <div class="res-match">
            <div class="res-team">
                <div class="res-team-name">{{ $partido->equipoLocal->nombre }}</div>
                <div class="res-team-role">Local</div>
            </div>
            <div class="res-vs">vs</div>
            <div class="res-team">
                <div class="res-team-name">{{ $partido->equipoVisitante->nombre }}</div>
                <div class="res-team-role">Visitante</div>
            </div>
        </div>

        {{-- Info de apuestas pendientes --}}
        @php $totalApuestas = $partido->apuestas()->where('estado', 'pendiente')->count(); @endphp
        @if($totalApuestas > 0)
        <div class="apuestas-info">
            ⚠️ Hay <strong>{{ $totalApuestas }} apuesta{{ $totalApuestas > 1 ? 's' : '' }} pendiente{{ $totalApuestas > 1 ? 's' : '' }}</strong>
            en este partido. Al confirmar se resolverán automáticamente.
        </div>
        @endif

        {{-- Formulario --}}
        <form method="POST" action="{{ route('admin.partidos.resultado', $partido) }}">
            @csrf

            @if($errors->any())
            <div style="background:#450a0a;border:1px solid #dc2626;color:#f87171;border-radius:8px;padding:10px 14px;margin-bottom:16px;font-size:.85rem">
                {{ $errors->first() }}
            </div>
            @endif

            <div class="score-grid">
                <div>
                    <div class="score-label">{{ $partido->equipoLocal->nombre }}</div>
                    <input type="number" name="goles_local" class="score-input"
                           min="0" max="20" value="{{ old('goles_local', 0) }}"
                           required autofocus>
                </div>
                <div class="score-dash">—</div>
                <div>
                    <div class="score-label">{{ $partido->equipoVisitante->nombre }}</div>
                    <input type="number" name="goles_visitante" class="score-input"
                           min="0" max="20" value="{{ old('goles_visitante', 0) }}"
                           required>
                </div>
            </div>

            <button type="submit" class="btn-registrar"
                    onclick="return confirm('¿Confirmar resultado?')">
                ✓ Confirmar resultado
            </button>
        </form>
    </div>
</div>
@endsection
