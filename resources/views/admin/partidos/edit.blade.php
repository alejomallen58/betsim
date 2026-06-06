@extends('layouts.app')
@section('title', 'Editar Cuotas')

@section('content')
<div class="max-w-lg mx-auto">
    <a href="{{ route('admin.partidos.index') }}" class="text-slate-400 hover:text-white text-sm mb-4 inline-block">← Volver</a>

    <h1 class="text-2xl font-bold text-white mb-2">Editar Cuotas</h1>
    <p class="text-slate-400 mb-6">
        {{ $partido->equipoLocal->nombre }} vs {{ $partido->equipoVisitante->nombre }}
    </p>

    <form method="POST" action="{{ route('admin.partidos.update', $partido) }}" class="card p-6 space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label>Cuota Local (1)</label>
                <input type="number" name="cuota_local" step="0.01" min="1.01"
                       value="{{ old('cuota_local', $partido->cuota->cuota_local) }}" required>
                @error('cuota_local') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label>Cuota Empate (X)</label>
                <input type="number" name="cuota_empate" step="0.01" min="1.01"
                       value="{{ old('cuota_empate', $partido->cuota->cuota_empate) }}" required>
                @error('cuota_empate') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label>Cuota Visitante (2)</label>
                <input type="number" name="cuota_visitante" step="0.01" min="1.01"
                       value="{{ old('cuota_visitante', $partido->cuota->cuota_visitante) }}" required>
                @error('cuota_visitante') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <button type="submit" class="btn-success">Guardar cambios</button>
    </form>
</div>
@endsection
