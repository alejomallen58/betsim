@extends('layouts.app')
@section('title', 'Nuevo Equipo')

@section('content')
<div class="max-w-lg mx-auto">
    <a href="{{ route('admin.equipos.index') }}" class="text-slate-400 hover:text-white text-sm mb-4 inline-block">← Volver</a>
    <h1 class="text-2xl font-bold text-white mb-6">Crear Equipo</h1>

    <form method="POST" action="{{ route('admin.equipos.store') }}" class="card p-6 space-y-4">
        @csrf
        <div>
            <label>Liga</label>
            <select name="liga_id" required>
                <option value="">Selecciona una liga</option>
                @foreach($ligas as $liga)
                    <option value="{{ $liga->id }}" {{ old('liga_id') == $liga->id ? 'selected' : '' }}>
                        {{ $liga->nombre }}
                    </option>
                @endforeach
            </select>
            @error('liga_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label>Nombre del equipo</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}" required>
            @error('nombre') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label>Ciudad</label>
            <input type="text" name="ciudad" value="{{ old('ciudad') }}">
        </div>
        <button type="submit" class="btn-success">Crear equipo</button>
    </form>
</div>
@endsection
