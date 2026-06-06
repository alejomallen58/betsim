@extends('layouts.app')
@section('title', 'Nuevo Partido')

@section('content')
<style>
    .form-wrap   { max-width: 720px; margin: 0 auto; }
    .form-card   { background: #1e293b; border: 1px solid #334155; border-radius: 12px; padding: 28px 24px; }
    .form-row    { margin-bottom: 16px; }
    .form-grid2  { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
    .form-grid3  { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 16px; }
    .form-actions { display: flex; gap: 12px; margin-top: 8px; }
    .err         { color: #f87171; font-size: .78rem; margin-top: 4px; }
    .page-title  { font-size: 1.4rem; font-weight: 700; color: #fff; margin: 0 0 20px 0; }
    .back-link   { color: #64748b; text-decoration: none; font-size: .875rem; display: inline-block; margin-bottom: 12px; }
    .back-link:hover { color: #fff; }

    @media (max-width: 600px) {
        .form-grid2, .form-grid3 { grid-template-columns: 1fr; }
    }
</style>

<div class="form-wrap">
    <a href="{{ route('admin.partidos.index') }}" class="back-link">← Volver</a>
    <h1 class="page-title">Crear Partido</h1>

    {{-- Errores globales --}}
    @if($errors->any())
    <div style="background:#450a0a;border:1px solid #dc2626;color:#f87171;border-radius:8px;padding:12px 16px;margin-bottom:16px">
        <strong>Corrige los siguientes errores:</strong>
        <ul style="margin:6px 0 0 16px">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.partidos.store') }}" class="form-card">
        @csrf

        {{-- Liga --}}
        <div class="form-row">
            <label>Liga</label>
            <select name="liga_id" required>
                <option value="">Selecciona una liga</option>
                @foreach($ligas as $liga)
                    <option value="{{ $liga->id }}" {{ old('liga_id') == $liga->id ? 'selected' : '' }}>
                        {{ $liga->nombre }}
                    </option>
                @endforeach
            </select>
            @error('liga_id') <div class="err">{{ $message }}</div> @enderror
        </div>

        {{-- Equipos --}}
        <div class="form-grid2">
            <div>
                <label>Equipo Local</label>
                <select name="equipo_local_id" required>
                    <option value="">Selecciona local</option>
                    @foreach($equipos as $equipo)
                        <option value="{{ $equipo->id }}" {{ old('equipo_local_id') == $equipo->id ? 'selected' : '' }}>
                            {{ $equipo->nombre }} ({{ $equipo->liga->nombre }})
                        </option>
                    @endforeach
                </select>
                @error('equipo_local_id') <div class="err">{{ $message }}</div> @enderror
            </div>
            <div>
                <label>Equipo Visitante</label>
                <select name="equipo_visitante_id" required>
                    <option value="">Selecciona visitante</option>
                    @foreach($equipos as $equipo)
                        <option value="{{ $equipo->id }}" {{ old('equipo_visitante_id') == $equipo->id ? 'selected' : '' }}>
                            {{ $equipo->nombre }} ({{ $equipo->liga->nombre }})
                        </option>
                    @endforeach
                </select>
                @error('equipo_visitante_id') <div class="err">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Fecha y jornada --}}
        <div class="form-grid2">
            <div>
                <label>Fecha y hora</label>
                <input type="datetime-local" name="fecha" value="{{ old('fecha') }}" required>
                @error('fecha') <div class="err">{{ $message }}</div> @enderror
            </div>
            <div>
                <label>Jornada <span style="color:#64748b;font-size:.75rem">(opcional)</span></label>
                <input type="text" name="jornada" value="{{ old('jornada') }}" placeholder="Ej: Jornada 35">
            </div>
        </div>

        {{-- Cuotas --}}
        <div class="form-grid3">
            <div>
                <label>Cuota Local (1)</label>
                <input type="number" name="cuota_local" step="0.01" min="1.01"
                       value="{{ old('cuota_local', '1.80') }}" required>
                @error('cuota_local') <div class="err">{{ $message }}</div> @enderror
            </div>
            <div>
                <label>Cuota Empate (X)</label>
                <input type="number" name="cuota_empate" step="0.01" min="1.01"
                       value="{{ old('cuota_empate', '3.40') }}" required>
                @error('cuota_empate') <div class="err">{{ $message }}</div> @enderror
            </div>
            <div>
                <label>Cuota Visitante (2)</label>
                <input type="number" name="cuota_visitante" step="0.01" min="1.01"
                       value="{{ old('cuota_visitante', '4.00') }}" required>
                @error('cuota_visitante') <div class="err">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-success">Crear Partido</button>
            <a href="{{ route('admin.partidos.index') }}" class="btn-primary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
