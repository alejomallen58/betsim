@extends('layouts.app')
@section('title', 'Editar Equipo')

@section('content')
<div class="max-w-lg mx-auto">
    <a href="{{ route('admin.equipos.index') }}" class="text-slate-400 hover:text-white text-sm mb-4 inline-block">← Volver</a>
    <h1 class="text-2xl font-bold text-white mb-6">Editar Equipo</h1>

    <form method="POST" action="{{ route('admin.equipos.update', $equipo) }}" class="card p-6 space-y-4">
        @csrf @method('PUT')
        <div>
            <label>Liga</label>
            <select name="liga_id" required>
                @foreach($ligas as $liga)
                    <option value="{{ $liga->id }}" {{ $equipo->liga_id == $liga->id ? 'selected' : '' }}>
                        {{ $liga->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Nombre</label>
            <input type="text" name="nombre" value="{{ old('nombre', $equipo->nombre) }}" required>
        </div>
        <div>
            <label>Ciudad</label>
            <input type="text" name="ciudad" value="{{ old('ciudad', $equipo->ciudad) }}">
        </div>
        <button type="submit" class="btn-success">Guardar</button>
    </form>
</div>
@endsection
